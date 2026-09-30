<?php
include_once(__DIR__ . "/includes/config.php");
include_once(__DIR__ . "/includes/classes/quiz.php");

session_start([
  "name" => "QUIZSESS"
]);

$quiz = new Quiz(QuizMode::from(QUIZ_MODE), QUIZ_MAX_ROUNDS , QUIZ_POINTS_PER_ANSWER);
$quiz->load_from_json($JSON_FILE);

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["answer"])) {

  $_SESSION = $quiz->process_request($_SESSION, $_POST);

} elseif ($_SERVER['REQUEST_METHOD'] === "GET") {
  
  $_SESSION = $quiz->process_request($_SESSION);

}

?>

<!DOCTYPE html>
<html lang="<?= QUIZ_LANG ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= QUIZ_TITLE ?></title>
  <link rel="shortcut icon" href="./img/favicon.png" type="image/png">
  <link rel="stylesheet" href="./css/style.css" />
  <script src="./js/script.js" type="text/javascript"></script>
</head>

<body>
  <div class="container">
    <div class="top-bar">
      <span class="m-2 text-bold text"><?= QUIZ_TITLE ?></span>
    </div>
    <?php
    if ($quiz->has_error()) {
      echo '<div class="text-shadow" style="color: red; font-weight: bolder; margin-top: 5rem;">'
        . $quiz->get_last_error() . '</div>';
      exit();
    }
    ?>
    <div id="quiz">
      <?php
      if (!$quiz->is_finished()) {
        if (isset($_SESSION["last_question"]) && !$quiz->get_question_is_done($_SESSION["last_question"])) {
          $current_question = $quiz->get_question($_SESSION["last_question"]);
        } else {
          $current_question = $quiz->get_next_question();
        }

        if (QUIZ_SHUFFLE_ANSWERS)
          $quiz->shuffle_answers($current_question->get_answers());
        ?>
        <div class="question-card" data-id="<?php echo $current_question->get_id(); ?>">
          <div class="question text-shadow">
            <?= $current_question->get_title(); ?>
          </div>

          <div class="answers">
            <form action="index.php" method="post" target="_self">
              <?php
              foreach ($current_question->get_answers() as $index => $answer) {
                if ($answer->get_id() == $current_question->get_solution()) {
                  if ($index % 2 != 0) {
                    echo "<input class=\"answer right text-shadow\" type=\"button\" answer-id=\"{$answer->get_id()}\" 
                                            onclick=\"onAnswerClicked(event, {$quiz->is_last_question()})\" value=\"{$answer->get_answer()}\" />";

                    echo '<div class="clear"></div>';

                  } else {
                    echo "<input class=\"answer left text-shadow\" type=\"button\" answer-id=\"{$answer->get_id()}\" 
                                            onclick=\"onAnswerClicked(event, {$quiz->is_last_question()})\" value=\"{$answer->get_answer()}\" />";
                  }
                } else {
                  if ($index % 2 != 0) {
                    echo "<input class=\"answer right text-shadow\" type=\"button\" 
                                            onclick=\"onAnswerClicked(event, {$quiz->is_last_question()})\" value=\"{$answer->get_answer()}\" />";

                    echo '<div class="clear"></div>';
                  } else {
                    echo "<input class=\"answer left text-shadow\" type=\"button\" 
                                            onclick=\"onAnswerClicked(event, {$quiz->is_last_question()})\" value=\"{$answer->get_answer()}\" />";
                  }
                }
              }

              $_SESSION["last_question"] = $current_question->get_id();
              ?>

              <input type="hidden" id="answer" name="answer" />
              <input type="submit" id="next_question" style="display: none;" value="Senden" />
            </form>
          </div>
        </div>
      <?php } else { ?>
        <div class="statistic-header text-antique text-shadow text-upper">Auswertung</div>

        <ul class="statistics">
          <?php
          foreach ($quiz->get_quiz_statistics() as $statictics) {
            if ($statictics["state"]) {
              echo "<li class=\"statistic-result\"><span class=\"statistic-state success\"></span>{$statictics["title"]}</li>";
            } else {
              echo "<li class=\"statistic-result\"><span class=\"statistic-state failed\"></span>{$statictics["title"]}</li>";
            }
          }
          ?>
        </ul>

        <form action="index.php" method="post" target="_self">
          <input type="button" class="btn-new-game text-shadow" onclick="startNewGame(event)" value="Neues Spiel" />
          <input type="submit" id="new_game" style="display: none;" value="Neues Spiel" />
        </form>

        <script>
          document.addEventListener("DOMContentLoaded", (evt) => {
            evt.preventDefault();

            createNotification("Quiz", "Bist du bereit für die nächste Runde?");
          });
        </script>
        <?php
        session_destroy();
      } ?>
    </div>

    <div class="points-overview text-bolder" onclick="turnUpDown(this)">
      <p class="text-shadow">
        Punktestand <?= $quiz->get_points() . " / " . ($quiz->get_questions_done() * QUIZ_POINTS_PER_ANSWER) ?>
      </p>
    </div>

    <div class="footer">
      <p class="m-1 text-bolder text-upper">Julia goes Dev</p>
      <a href="https://www.twitch.tv/juliagoesdev" target="_blank">
        <img src="img/twitch-48.png" alt="Twitch Logo" />
      </a>
      <a href="https://www.instagram.com/julia.goes.dev" target="_blank">
        <img src="img/instagram-48.png" alt="Insta Logo" />
      </a>
      <a href="https://discord.gg/hU3Q9xDEr" target="_blank">
        <img src="img/discord-48.png" alt="Discord Logo" />
      </a>
    </div>

    <div id="notifications" class="top-right"></div>
  </div>
</body>
</html>