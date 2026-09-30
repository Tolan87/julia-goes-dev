<?php
include_once(__DIR__ . "/answer.php");

class Question
{
  public function __construct(private int $id, private string $title, private array $answers, private int $solution_id) 
  {
    $this->id = $id;
    $this->title = $title;
    $this->answers = $answers;
    $this->solution_id = $solution_id;
  }

  public function get_id(): int
  {
    return $this->id;
  }

  public function get_title(): string
  {
    return $this->title;
  }

  public function &get_answers(): array
  {
    return $this->answers;
  }

  public function get_solution(): int
  {
    return $this->solution_id;
  }
}

?>