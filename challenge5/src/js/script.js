const config = {
  showNotificationDuration: 5000,
  nextQuestionDuration: 6000,
  fadeOutDuration: 480, // should be near the animation duration is set in css
  newGameDuration: 2800 // It´s depend on the fall animation + shake effect
};

window.addEventListener("DOMContentLoaded", (evt) => {
  evt.preventDefault();

  setTimeout(() => {
    let points_ov = document.getElementsByClassName("points-overview");
    points_ov[0].classList.add("turn-down");
  }, 1000);
});

function startNewGame(evt) {
  let formNode = evt.target.parentNode;

  launchFall();

  setTimeout(() => {
    document.getElementById("new_game").click();
  }, config.newGameDuration);
}

function onAnswerClicked(evt, isLastQuestion = false) {
  let formNode = evt.target.parentNode;
  let selectedAnswer = evt.target;

  selectedAnswer.classList.add("marked");

  let questionNode = formNode.parentNode.parentNode;
  let questionID = questionNode.getAttribute("data-id");
  let answerIsSolution = selectedAnswer.hasAttribute("answer-id");

  document.getElementById("answer").value = '{ "question_id": ' + questionID + ', "is_solution": ' + answerIsSolution + ' }';

  for (let i = 0; i < formNode.children.length; i++) {
    if (formNode.children[i].type == "submit" || formNode.children[i].type == "hidden")
      continue;
    else {
      if (formNode.children[i].hasAttribute("answer-id")) {
        formNode.children[i].classList.add("mark-correct");
      }

      formNode.children[i].disabled = true;
    }
  }

  if (!isLastQuestion)
  {
    createNotification("Quiz-Leiter", "Die nächste Frage kommt in " + (config.nextQuestionDuration / 1000) + " Sekunden...");
  } else {
    createNotification("Quiz-Leiter", "Das war die letzte Frage, auf gehts zur Ausertung...");
  }

  setTimeout(() => {
    document.getElementById("next_question").click();
  }, config.nextQuestionDuration);
}

function turnUpDown(element) {
  if (element.classList.contains("turn-down")) {
    element.classList.remove("turn-down");
    element.classList.add("turn-up");
  } else if (element.classList.contains("turn-up")) {
    element.classList.remove("turn-up");
    element.classList.add("turn-down");
  }
}

function fadeInOut(element) {
  if (element.classList.contains("fade-out")) {
    element.classList.remove("fade-out");
    element.classList.add("fade-in");
  } else if(element.classList.contains("fade-in")) {
    element.classList.remove("fade-in");
    element.classList.add("fade-out");
  }
}

function shakeIt(element) {
  if (element.classList.contains("shake-effect"))
    element.classList.remove("shake-effect");

  element.classList.add("shake-effect");

  setTimeout(() => {
    element.classList.remove("shake-effect");
  }, 300);
}

function launchFall() {
  let points_ov = document.getElementsByClassName("points-overview");
  points_ov[0].classList.add("shake-effect");

  setTimeout(() => {
    points_ov[0].classList.add("fall");
  }, 200);
}

function createNotification(title, message, duration = config.showNotificationDuration) {
  let notificationsNode = document.getElementById("notifications");

  // Maximal 10 notifications at same time
  if (notificationsNode.childNodes.length > 10)
    return;

  let notification = document.createElement("div");
  notification.classList.add("notification", "fade-in");

  let notificationHeader = document.createElement("div");
  notificationHeader.classList.add("notification-header", "text-burly");

  let notificationHeaderText = document.createElement("span");
  notificationHeaderText.innerText = title;

  notificationHeader.appendChild(notificationHeaderText);

  /* If duration is lesser than zero or equal zero
   * add an close button to notification window
  */
  if (duration <= 0) {
    let notificationHeaderClose = document.createElement("input");
    notificationHeaderClose.classList.add("btn", "text-bold", "text-upper");
    notificationHeaderClose.type = "button";
    notificationHeaderClose.value = "X";
    notificationHeaderClose.onclick = closeNotification;

    notificationHeader.appendChild(notificationHeaderClose);
  }

  let notificationMessage = document.createElement("div");
  notificationMessage.classList.add("notification-message");
  notificationMessage.innerText = message;

  notification.appendChild(notificationHeader);
  notification.appendChild(notificationMessage);

  if (notificationsNode.firstElementChild == null) {
    notificationsNode.appendChild(notification);
  }
  else {
    notificationsNode.firstChild.before(notification);
  }

  /* If duration is greater than zero ensure
   * our fade out animation is finished before
   * remove the notification window
  */
  if (duration > 0) {
    setTimeout(() => {
      fadeInOut(notification);
    }, (duration - 480));

    setTimeout(() => {
      notificationsNode.removeChild(notification);
    }, duration);
  }
}

function closeNotification(evt) {
  fadeInOut(evt.target.parentNode.parentNode);

  /* If closing notification window per close button
   * ensure our fade out animation is finished before
   * remove the notification window
  */
  setTimeout(() => {
    let notificationsNode = document.getElementById("notifications");
    notificationsNode.removeChild(evt.target.parentNode.parentNode);
  }, 480);
}
