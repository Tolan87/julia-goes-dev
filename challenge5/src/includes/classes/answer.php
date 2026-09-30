<?php

class Answer
{
  public function __construct(private int $id, private string $answer)
  {
    $this->id = $id;
    $this->answer = $answer;
  }

  public function get_id(): int
  {
    return $this->id;
  }

  public function get_answer(): string
  {
    return $this->answer;
  }
}

?>