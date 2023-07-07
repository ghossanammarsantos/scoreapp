<?php
require_once 'db.php';


// Fungsi untuk menambah klub
function addClub($name, $city) {
    $connection = connectDatabase();

    $name = mysqli_real_escape_string($connection, $name);
    $city = mysqli_real_escape_string($connection, $city);

    $query = "INSERT INTO clubs (name, city) VALUES ('$name', '$city')";
    mysqli_query($connection, $query);

    closeDatabase($connection);
}

// Fungsi untuk menambah pertandingan
function addMatch($homeClubId, $awayClubId, $homeGoals, $awayGoals)
{
    $connection = connectDatabase();

    $homeGoals = (int) $homeGoals;
    $awayGoals = (int) $awayGoals;

    $query = "INSERT INTO matches (home_club_id, away_club_id, home_goals, away_goals) VALUES ($homeClubId, $awayClubId, $homeGoals, $awayGoals)";
    mysqli_query($connection, $query);

    closeDatabase($connection);
}

// Fungsi untuk mendapatkan daftar klub
function getClubs() {
    $connection = connectDatabase();

    $query = "SELECT * FROM clubs";
    $result = mysqli_query($connection, $query);

    $clubs = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $clubs[] = $row;
    }

    closeDatabase($connection);

    return $clubs;
}


// Fungsi untuk mendapatkan hasil pertandingan
function getMatches()
{
    $connection = connectDatabase();

    $query = "SELECT matches.*, home_club.name AS home_club_name, away_club.name AS away_club_name FROM matches
              INNER JOIN clubs AS home_club ON matches.home_club_id = home_club.id
              INNER JOIN clubs AS away_club ON matches.away_club_id = away_club.id";
    $result = mysqli_query($connection, $query);

    $matches = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $match = array(
            'id' => $row['id'],
            'home_club_id' => $row['home_club_id'],
            'away_club_id' => $row['away_club_id'],
            'home_club_name' => $row['home_club_name'],
            'away_club_name' => $row['away_club_name'],
            'home_goals' => $row['home_goals'],
            'away_goals' => $row['away_goals']
        );
        $matches[] = $match;
    }

    closeDatabase($connection);

    return $matches;
}


// Fungsi untuk menghitung klasemen
function calculateStandings() {
    $clubs = getClubs();
    $matches = getMatches();

    $standings = array();
    foreach ($clubs as $club) {
        $standings[$club['id']] = array(
            'id' => $club['id'],
            'name' => $club['name'],
            'matches_played' => 0,
            'matches_won' => 0,
            'matches_drawn' => 0,
            'matches_lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'points' => 0
        );
    }

    foreach ($matches as $match) {
        $homeClubId = $match['home_club_id'];
        $awayClubId = $match['away_club_id'];
        $homeGoals = $match['home_goals'];
        $awayGoals = $match['away_goals'];

        // Update jumlah pertandingan yang dimainkan
        $standings[$homeClubId]['matches_played']++;
        $standings[$awayClubId]['matches_played']++;

        // Update jumlah gol yang dicetak dan kebobolan
        $standings[$homeClubId]['goals_for'] += $homeGoals;
        $standings[$homeClubId]['goals_against'] += $awayGoals;
        $standings[$awayClubId]['goals_for'] += $awayGoals;
        $standings[$awayClubId]['goals_against'] += $homeGoals;

        // Update hasil pertandingan (menang, seri, kalah)
        if ($homeGoals > $awayGoals) {
            $standings[$homeClubId]['matches_won']++;
            $standings[$homeClubId]['points'] += 3;
            $standings[$awayClubId]['matches_lost']++;
        } elseif ($homeGoals < $awayGoals) {
            $standings[$homeClubId]['matches_lost']++;
            $standings[$awayClubId]['matches_won']++;
            $standings[$awayClubId]['points'] += 3;
        } else {
            $standings[$homeClubId]['matches_drawn']++;
            $standings[$homeClubId]['points'] += 1;
            $standings[$awayClubId]['matches_drawn']++;
            $standings[$awayClubId]['points'] += 1;
        }
    }

    // Urutkan klasemen berdasarkan poin, selisih gol, dan gol dicetak
    usort($standings, function($a, $b) {
        if ($a['points'] === $b['points']) {
            $a_goal_difference = $a['goals_for'] - $a['goals_against'];
            $b_goal_difference = $b['goals_for'] - $b['goals_against'];
            if ($a_goal_difference === $b_goal_difference) {
                return $b['goals_for'] - $a['goals_for'];
            }
            return $b_goal_difference - $a_goal_difference;
        }
        return $b['points'] - $a['points'];
    });

    return $standings;
}

// Fungsi untuk menampilkan klasemen
function showStandings() {
    $standings = calculateStandings();

    echo '
    <table border="1">
        <tr>
            <th>No</th>
            <th>Klub</th>
            <th>Ma</th>
            <th>Me</th>
            <th>S</th>
            <th>K</th>
            <th>GM</th>
            <th>GK</th>
            <th>Point</th>
        </tr>';

    $no = 1;
    foreach ($standings as $club) {
        echo '
        <tr>
            <td>'.$no.'</td>
            <td>'.$club['name'].'</td>
            <td>'.$club['matches_played'].'</td>
            <td>'.$club['matches_won'].'</td>
            <td>'.$club['matches_drawn'].'</td>
            <td>'.$club['matches_lost'].'</td>
            <td>'.$club['goals_for'].'</td>
            <td>'.$club['goals_against'].'</td>
            <td>'.$club['points'].'</td>
        </tr>';
        $no++;
    }

    echo '</table>';
}

function isClubExist($name, $city) {
    $connection = connectDatabase();

    $name = mysqli_real_escape_string($connection, $name);
    $city = mysqli_real_escape_string($connection, $city);

    $query = "SELECT * FROM clubs WHERE name = '$name' AND city = '$city'";
    $result = mysqli_query($connection, $query);

    $count = mysqli_num_rows($result);

    closeDatabase($connection);

    return $count > 0;
}

// Fungsi untuk memeriksa apakah pertandingan sudah ada
function isMatchExist($homeClubId, $awayClubId)
{
    $connection = connectDatabase();

    $query = "SELECT COUNT(*) as count FROM matches WHERE home_club_id = $homeClubId AND away_club_id = $awayClubId";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    closeDatabase($connection);

    return $count > 0;
}

// Fungsi untuk memeriksa apakah semua pertandingan sudah ada
function areMatchesExist($matches)
{
    foreach ($matches as $match) {
        $homeClubId = $match['home_club_id'];
        $awayClubId = $match['away_club_id'];

        if (isMatchExist($homeClubId, $awayClubId)) {
            return true;
        }
    }

    return false;
}
?>
