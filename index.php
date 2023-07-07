<!DOCTYPE html>
<html>
<head>
    <title>Tes Coding - Klasemen Sepak Bola</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Klasemen Sepak Bola</h1>
   
    <?php
 
    // Include file functions.php dan db.php
    require_once 'functions.php';
    require_once 'db.php';

    // Proses penambahan data klub
    if (isset($_POST['add_club'])) {
        $name = $_POST['name'];
        $city = $_POST['city'];

        if (isClubExist($name, $city)) {
            echo '<p class="error">Data klub sudah ada</p>';
        } else {
            addClub($name, $city);
        }
    }

    // Proses penambahan data pertandingan (satu per satu)
    if (isset($_POST['add_single_match'])) {
        $homeClubId = $_POST['home_club'];
        $awayClubId = $_POST['away_club'];
        $homeGoals = $_POST['home_goals'];
        $awayGoals = $_POST['away_goals'];

        if (isMatchExist($homeClubId, $awayClubId)) {
            echo '<p class="error">Data pertandingan sudah ada</p>';
        } else {
            addMatch($homeClubId, $awayClubId, $homeGoals, $awayGoals);
        }
    }

    // Proses penambahan data pertandingan (multiple)
    if (isset($_POST['add_multiple_match'])) {
        $homeClubs = $_POST['home_club'];
        $awayClubs = $_POST['away_club'];
        $homeGoals = $_POST['home_goals'];
        $awayGoals = $_POST['away_goals'];

        if (areMatchesExist($homeClubs, $awayClubs)) {
            echo '<p class="error">Data pertandingan sudah ada</p>';
        } else {
            $matchCount = count($homeClubs);
            for ($i = 0; $i < $matchCount; $i++) {
                addMatch($homeClubs[$i], $awayClubs[$i], $homeGoals[$i], $awayGoals[$i]);
            }
        }
    }
    ?>

    <!-- Form input data klub -->
    <h2>Input Data Klub</h2>
    <form action="" method="POST">
        <label for="name">Nama Klub:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="city">Kota Klub:</label>
        <input type="text" id="city" name="city" required>
        <br>
        <input type="submit" name="add_club" value="Save">
    </form>

    <!-- Form input skor pertandingan (satu per satu) -->
    <h2>Input Skor Pertandingan (Satu per Satu)</h2>
    <form action="" method="POST">
        <label for="home_club">Klub Tuan Rumah:</label>
        <select name="home_club" id="home_club" required>
            <option value="">Pilih Klub</option>
            <?php
            $clubs = getClubs();
            foreach ($clubs as $club) {
                echo '<option value="'.$club['id'].'">'.$club['name'].'</option>';
            }
            ?>
        </select>
        <br>
        <label for="away_club">Klub Tamu:</label>
        <select name="away_club" id="away_club" required>
            <option value="">Pilih Klub</option>
            <?php
            foreach ($clubs as $club) {
                echo '<option value="'.$club['id'].'">'.$club['name'].'</option>';
            }
            ?>
        </select>
        <br>
        <label for="home_goals">Skor Tuan Rumah:</label>
        <input type="number" id="home_goals" name="home_goals" required>
        <br>
        <label for="away_goals">Skor Tamu:</label>
        <input type="number" id="away_goals" name="away_goals" required>
        <br>
        <input type="submit" name="add_single_match" value="Save">
    </form>

    <!-- Form input skor pertandingan (multiple) -->
    <h2>Input Skor Pertandingan (Multiple)</h2>
    <form action="" method="POST">
        <div id="match_container">
            <div class="match">
                <label for="home_club[]">Klub Tuan Rumah:</label>
                <select name="home_club[]" required>
                    <option value="">Pilih Klub</option>
                    <?php
                    foreach ($clubs as $club) {
                        echo '<option value="'.$club['id'].'">'.$club['name'].'</option>';
                    }
                    ?>
                </select>
                <br>
                <label for="away_club[]">Klub Tamu:</label>
                <select name="away_club[]" required>
                    <option value="">Pilih Klub</option>
                    <?php
                    foreach ($clubs as $club) {
                        echo '<option value="'.$club['id'].'">'.$club['name'].'</option>';
                    }
                    ?>
                </select>
                <br>
                <label for="home_goals[]">Skor Tuan Rumah:</label>
                <input type="number" name="home_goals[]" required>
                <br>
                <label for="away_goals[]">Skor Tamu:</label>
                <input type="number" name="away_goals[]" required>
                <br>
            </div>
        </div>
        <button type="button" onclick="addMatch()">Add</button>
        <input type="submit" name="add_multiple_match" value="Save">
    </form>

    <!-- Tampilan klasemen -->
    <h2>Tampilan Klasemen</h2>
    <?php showStandings(); ?>

    <script>
        function addMatch() {
            var matchContainer = document.getElementById("match_container");
            var matchDiv = document.createElement("div");
            matchDiv.classList.add("match");

            var homeClubSelect = document.createElement("select");
            homeClubSelect.setAttribute("name", "home_club[]");
            homeClubSelect.setAttribute("required", true);
            var homeClubOption = document.createElement("option");
            homeClubOption.setAttribute("value", "");
            homeClubOption.textContent = "Pilih Klub";
            homeClubSelect.appendChild(homeClubOption);
            <?php
            foreach ($clubs as $club) {
                echo 'var homeClubOption'.$club['id'].' = document.createElement("option");
                homeClubOption'.$club['id'].'.setAttribute("value", "'.$club['id'].'");
                homeClubOption'.$club['id'].'.textContent = "'.$club['name'].'";
                homeClubSelect.appendChild(homeClubOption'.$club['id'].');';
            }
            ?>

            var awayClubSelect = document.createElement("select");
            awayClubSelect.setAttribute("name", "away_club[]");
            awayClubSelect.setAttribute("required", true);
            var awayClubOption = document.createElement("option");
            awayClubOption.setAttribute("value", "");
            awayClubOption.textContent = "Pilih Klub";
            awayClubSelect.appendChild(awayClubOption);
            <?php
            foreach ($clubs as $club) {
                echo 'var awayClubOption'.$club['id'].' = document.createElement("option");
                awayClubOption'.$club['id'].'.setAttribute("value", "'.$club['id'].'");
                awayClubOption'.$club['id'].'.textContent = "'.$club['name'].'";
                awayClubSelect.appendChild(awayClubOption'.$club['id'].');';
            }
            ?>

            var homeGoalsInput = document.createElement("input");
            homeGoalsInput.setAttribute("type", "number");
            homeGoalsInput.setAttribute("name", "home_goals[]");
            homeGoalsInput.setAttribute("required", true);

            var awayGoalsInput = document.createElement("input");
            awayGoalsInput.setAttribute("type", "number");
            awayGoalsInput.setAttribute("name", "away_goals[]");
            awayGoalsInput.setAttribute("required", true);

            matchDiv.appendChild(homeClubSelect);
            matchDiv.appendChild(document.createElement("br"));
            matchDiv.appendChild(awayClubSelect);
            matchDiv.appendChild(document.createElement("br"));
            matchDiv.appendChild(homeGoalsInput);
            matchDiv.appendChild(document.createElement("br"));
            matchDiv.appendChild(awayGoalsInput);
            matchDiv.appendChild(document.createElement("br"));

            matchContainer.appendChild(matchDiv);
        }
    </script>
</body>
</html>
