<?php

/*
 * Title of the quiz
 */
const QUIZ_TITLE = "Quiz - Allgemeinwissen";

/*
 * Language [Not Yet Implemented]
 * Tag is used for the 'html' Tag attribute lang
 */
const QUIZ_LANG = "de-DE";

/*
 * Mode the is used, it can be use ordered questions or random
 * 0 -> Questions are ordered
 * 1 -> Questions are random
 */
const QUIZ_MODE = 1;

/*
 * Shuffle is used to randomize the order of answers 
 * instead of showing them in a fixed sequence
 * 0 -> Fixed order
 * 1 -> Random order
 */
const QUIZ_SHUFFLE_ANSWERS = 1;

/*
 * Points given for correct answers
 */
const QUIZ_POINTS_PER_ANSWER = 3;

/*
 * Rounds to play before quiz is finished
 */
const QUIZ_MAX_ROUNDS = 5;

/*
 * Path to json file
 * const variables can not use __DIR__ or 
 * functions that the reason to use a normal variable
 */
$JSON_FILE = dirname(__DIR__) . "/quiz.json";
?>