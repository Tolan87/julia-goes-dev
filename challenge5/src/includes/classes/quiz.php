<?php
include_once(__DIR__ . "/question.php");

enum QuizMode: int
{
  case Ordered = 0;
  case Random = 1;
}

enum QuizError: int
{
  case QUIZ_NO_ERROR = 0;
  case QUIZ_JSON_NOT_FOUND = 1;
  case QUIZ_JSON_NOT_VAILD = 2;
  case QUIZ_QUESTIONS_EMPTY = 3;
  case QUIZ_TOO_MANY_ROUNDS = 4;
}

class Quiz
{
  private QuizMode $mode = QuizMode::Ordered;
  private QuizError $last_error = QuizError::QUIZ_NO_ERROR;
  private int $max_rounds = 0;

  private int $points_per_answer = 0;
  private array $questions = [];
  private array $questions_done = [];

  public function __construct(QuizMode $mode = QuizMode::Ordered, int $max_rounds, int $points_per_answer)
  {
    $this->points_per_answer = $points_per_answer;
    $this->mode = $mode;
    $this->max_rounds = $max_rounds;
  }

  /**
   * Load and validate the json file
   * 
   * @param string $$file File path of the json file
   * @return bool Returns whether loading the file succeeded or failed
  **/
  public function load_from_json(string $file): bool
  {
    if (file_exists($file)) {
      $json = file_get_contents($file);

      if (!json_validate($json)) {
        $this->last_error = QuizError::QUIZ_JSON_NOT_VAILD;
        return false;
      }

      $json_parsed = json_decode($json, true);

      if (!isset($json_parsed["quiz"])) {
        $this->last_error = QuizError::QUIZ_JSON_NOT_VAILD;
        return false;
      } elseif (!isset($json_parsed["quiz"]["questions"])) {
        $this->last_error = QuizError::QUIZ_QUESTIONS_EMPTY;
        return false;
      }

      foreach ($json_parsed["quiz"]["questions"] as $question) {
        $answers = [];
        foreach ($question["answers"] as $answer) {
          $answers[] = new Answer($answer["id"], $answer["answer"]);
        }

        $quest = new Question($question["id"], $question["title"], $answers, $question["solution_id"]);
        $this->questions[] = $quest;
      }

      $this->validate();

      return true;
    }

    $this->last_error = QuizError::QUIZ_JSON_NOT_FOUND;
    return false;
  }

  /**
   * Processes requests and manage session variables
   *
   * @param array $sessionData Include the `values` of global `$_SESSION` variable
   * @param array $postData Include the `values` of global `$_POST` variable, can be empty
   * @return array Returns an array with sesssion variables
  **/
  public function process_request(array $sessionData, array $postData = []) : array
  {
    if (isset($sessionData["questions_done"])) {
      foreach ($sessionData["questions_done"] as $value) {
        $this->set_question_is_done($value["question_id"], $value["is_solution"]);
      }
    }

    if (isset($postData["answer"]))
    {
      $question_result = json_decode($postData["answer"], true);

      if (!isset($sessionData["questions_done"])) {
        $sessionData["questions_done"] = [];
        $sessionData["questions_done"][] = $question_result;

        $this->set_question_is_done($question_result["question_id"], $question_result["is_solution"]);
      } else {

        if (!$this->get_question_is_done($question_result["question_id"])) {
          $sessionData["questions_done"][] = $question_result;

          $this->set_question_is_done($question_result["question_id"], $question_result["is_solution"]);
        }
      }
    }

    return $sessionData;
  }

  /**
   * Check if there was an error
   * 
   * @return bool 
  **/
  public function has_error(): bool
  {
    return $this->last_error != QuizError::QUIZ_NO_ERROR;
  }

  /**
   * Get the last error
   * 
   * @return string|null Returns an string if there was an error otherwise null
  **/
  public function get_last_error(): string|null
  {
    return match ($this->last_error) {
      QuizError::QUIZ_JSON_NOT_FOUND => "Json file was not found...",
      QuizError::QUIZ_JSON_NOT_VAILD => "Json Format is not valid, parsing failed...",
      QuizError::QUIZ_QUESTIONS_EMPTY => "Your provided json file does not contains any questions...",
      QuizError::QUIZ_TOO_MANY_ROUNDS => "Your provided questions catalog is lesser than the max rounds to play...",
      default => null,
    };
  }

  /**
   * Get current quiz mode
   * 
   * @return QuizMode Returns current mode as enumeration
  **/
  public function get_mode(): QuizMode
  {
    return $this->mode;
  }

  /**
   * Set current quiz mode
   *
   * @param QuizMode $mode New mode
   * @return void
  **/
  public function set_mode(QuizMode $mode): void
  {
    if ($this->mode == $mode)
      return;

    $this->mode = $mode;
  }

  /**
   * Check if the quiz is over
   * 
   * @return bool Returns true or false
  **/
  public function is_finished(): bool
  {
    return count($this->questions_done) >= $this->max_rounds;
  }

  /**
   * Check is last question
   * 
   * @return bool Returns true or false
  **/
  public function is_last_question(): bool
  {
    return count($this->questions_done) == ($this->max_rounds -1);
  }

  /**
   * Get current points
   * 
   * @return int Returns current amount of points
  **/
  public function get_points(): int
  {
    $correct_answers = array_filter($this->questions_done, 
      function ($question) { return $question["is_solution"] == true; });

    return count($correct_answers) * $this->points_per_answer;
  }

  /**
   * Count quiz statictics
   * 
   * @return array|null Returns an array containing `title` and `state` of all answered questions or null
  **/ 
  public function get_quiz_statistics(): array|null
  {
    $results = [];

    foreach ($this->questions_done as $question_done) {
      foreach ($this->questions as $question) {
        if ($question->get_id() == $question_done["question_id"]) {
          $results[] = ["title" => $question->get_title(), "state" => $question_done["is_solution"]];
          continue;
        }
      }
    }

    return $results ?? null;
  }

  /**
   * Get a question by their id
   * 
   * @param int $question_id Id of the question
   * @return Question|bool Returns the question if it exists or false
  **/
  public function get_question(int $question_id): Question|null
  {
    $index = $this->question_exists($question_id);

    return $index != false ? $this->questions[$index] : null;
  }

   /**
   * Gets the count of questions that have been done
   * 
   * @return int Returns the count of completed questions
  **/
  public function get_questions_done(): int
  {
    return count($this->questions_done);
  }

  /**
   * Marks a question as done
   * 
   * @param int $question_id Id of the question
   * @param bool $is_solution Is the answer correct or incorrect
   * @return void
  **/
  public function set_question_is_done(int $question_id, bool $is_solution): void
  {
    $this->questions_done[] = [
      "question_id" => $question_id,
      "is_solution" => $is_solution
    ];
  }

  /**
   * Checks is a question done
   * 
   * @param int $question_id Id of the question
   * @return bool Returns true if question done otherwise false
  **/
  public function get_question_is_done(int $question_id): bool
  {
    foreach ($this->questions_done as $question_done) {
      if ($question_done["question_id"] == $question_id)
        return true;
    }

    return false;
  }

  /**
   * Gets the next question depending on the mode
   * 
   * @return Question Returns a question
  **/
  public function get_next_question(): Question
  {
    if ($this->mode == QuizMode::Ordered) {
      if (0 === $index = count($this->questions_done)) {
        return $this->questions[0];
      }

      return $this->questions[$index];
    }

    return $this->questions[$this->get_random_question()];
  }

  /**
   * Get all questions
   * 
   * @return array Returns an array of all questions
  **/
  public function get_questions(): array
  {
    return $this->questions;
  }

  /**
   * Shuffles the answers to add some variety
   * 
   * @return void
  **/
  public function shuffle_answers(array &$answers): void
  {
    shuffle($answers);
  }

  /**
   * Retrieves the index of a random unanswered question.
   *
   * @return int|null The array index of a randomly selected, unanswered question or null if all questions are done
  **/
  private function get_random_question(): int|null
  {
    $availableQuestions = array_filter($this->questions, function ($question) {
          return !$this->get_question_is_done($question->get_id());
      });

    if (empty($availableQuestions))
      return null;

    return array_rand($availableQuestions);
  }

  /**
   * Checks if a question with the given ID exists in the questions list.
   *
   * @param int|string $question_id The unique identifier of the question to search for.
   * @return int|bool Returns the array index (int) if the question exists, or false if not found.
  **/
  private function question_exists($question_id): int|bool
  {
    foreach ($this->questions as $index => $question) {
      if ($question->get_id() == $question_id) {
        return $index;
      }
    }

    return false;
  }

  /**
   * Validate that maximal rounds are not greater than maximal questions
   * 
   * It can be possible to add more checks here
   *
   * @return void
  **/
  private function validate(): void
  {
    if ($this->max_rounds > count($this->questions)) {
      $this->last_error = QuizError::QUIZ_TOO_MANY_ROUNDS;
      return;
    }
  }
}

?>