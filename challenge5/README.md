### Usage
---
Ihr habt hier zwei Möglichkeiten, einmal den Inhalt aus dem src Verzeichnis einfach in eurer Verzeichnis des Webserver (mit PHP Unterstützung) kopieren und dann aufrufen oder die zweite Möglichkeit, wenn Ihr Docker nutzt euch einen vorkonfigurierten Container zu erstellen.


Im Verzeichniss mit der `Dockerfile` ein Terminal öffnen und folgenden Befehl absetzen
```
docker compose up -d
```

Das Quiz ist dann unter `http://127.0.0.1` im Browser erreichbar

mit dem dem Befehl könnt ihr den Container wieder beenden
```
docker compose down
```

### Demo
---
Eine Demo des Quizes gibt es unter `http://quiz.tolan.bplaced.net`

#### Erklärung zur Lösung
---

Der ein oder andere versierte Programmierer unter euch wird sich vielleicht die Frage stellen, warum der Code hier so entstanden ist. Dazu kurz ein kleinen Absatz.

Ich bin beruflich kein Entwickler und habe mir soweit alles selbst über Bücher und andere Wege beigebracht. Dazu kommt, dass ich lange nicht mit PHP gearbeitet habe und sonst eigentlich eher in .Net mit C# unterwegs bin.

Es ist mir durchaus bewusst, dass man die Lösung wie ich sie hier habe so nicht wählen würde und mit einer REST API (Controllern, Services, Routing, Fetch API etc.) arbeiten würde. Ich habe leider die "Bosstracker" Challenge von davor mit dieser hier zusammen gewürfelt, dessen Anforderungen es war mit $_GET, $POST und Formularen zu arbeiten, als mir dies auffiel wollte ich es auch nicht mehr ändern, da es meiner Meinung nach eben auch seine eigenen Anforderungen mit sich gebracht hat.

Es ist mir ebenfalls geläufig, das man Requests und Sessions in eigenen Klassen abhandelt würde oder sogar Frameworks nutzt, dies finde ich für die Challenge aber zu viel overhead, auch mit dem Hintergedanken, dass es sich eventuell auch Einsteiger anschauen und es relativ einfach und nachvollziehbar bleiben soll und man vielleicht auch etwas dabei lernen kann.

Auch weiß ich von eigentlich noch offenen Problemen mit diesem Ansatz, wofür ich auch Lösungen hätte, die mir aber persönlich nicht gefallen hätten beim Ablauf des Quizes oder ich Aufgrund von Zeitmangel nicht mehr umsetzen konnte.

Ich hatte bei der Challenge sehr viel Spaß (1/3 der Zeit selbst gespielt) und auch eine Auffrischung in PHP. Ich habe für das programmieren keinerlei KI genutzt. KI war nur bei der erweiterungen des Fragenkatalogs von 10 auf 200 Fragen im Spiel.

### Aufgabe
---
Julia Goes Dev - Challenge #5 — Das Fandom-Quiz 

Sprache: frei wählbar — läuft aber besonders schön in PHP!

Nach dem Boss-Tracker letzten Monat wechseln wir das Terrain: diesmal geht's nicht um Rollenspiel-Bosse, sondern um euer eigenes Quiz — Thema frei wählbar, mein Vorschlag: Anime & Manga. Diese Runde bauen wir ein Quiz-Spiel — mehrere Fragen, mehrere Antwortmöglichkeiten, am Ende ein Ergebnis. Das ist die Challenge wo zum ersten Mal Funktionen richtig wichtig werden: Statt euren Code einmal von oben nach unten zu schreiben, teilt ihr ihn in wiederverwendbare Bausteine auf — eine Funktion die eine Frage stellt, eine die den Punktestand zählt, eine die am Ende auswertet.

Das Thema ist komplett frei — Anime/Manga-Wissen, euer Lieblingsspiel, Allgemeinwissen, was auch immer euch Spaß macht. Hauptsache es sind eure eigenen Fragen.

Beispiel:
```php
Frage 3 von 5 

"Welches Element beherrscht der Protagonist in [euer Lieblingsanime]?"

A) Feuer B) Wasser C) Erde D) Blitz → Richtig! Punktestand: 3/3 
```

#### Stufe 1 — Pflicht
---

Drei Fragen, richtig oder falsch
Legt 3 Fragen mit jeweils einer richtigen Antwort fest (fest im Code, keine Eingabe nötig). Gebt für jede Frage aus ob die hinterlegte Antwort richtig oder falsch wäre. Baut dafür schon eine kleine Funktion, die eine Frage auswertet — auch wenn ihr sie nur 3x aufruft.

Funktionen Arrays Bedingungen

#### Stufe 2 — Optional, aber du schaffst das
---

Echtes Quiz mit Nutzereingabe
Der Nutzer beantwortet jede Frage selbst (Eingabe oder Auswahl per Formular/Buttons). Ein Punktestand wird mitgezählt und am Ende ausgewertet — inklusive Mehrfachauswahl (A/B/C/D) statt nur richtig/falsch.

Funktionen Schleifen Punktezähler

Ab dieser Stufe ist KI-Unterstützung erlaubt — bitte bei der Abgabe kennzeichnen, wo sie genutzt wurde.

#### Stufe 3 — Für die die nicht aufhören können
---

Zufällige Reihenfolge & Auswertung mit Persönlichkeit
Fragen erscheinen in zufälliger Reihenfolge, es gibt einen Timer oder eine Bestenliste — und am Ende gibt's je nach Punktzahl eine individuelle Auswertung ("Ihr seid ein wahrer Kenner!" / "Zeit nochmal reinzuschauen!"). Bonus: Fragen aus einer externen Datei (CSV/JSON) statt fest im Code.
ZufallsfunktionenDatei-ImportVerschachtelte Logik

Auch hier gilt: KI-Nutzung erlaubt, aber offen angeben.

Zur Regel: Stufe 1 bleibt KI-frei — Funktionen und Grundlogik sollt ihr selbst durchdenken. Ab Stufe 2 dürft ihr KI nutzen, wenn ihr es bei der Abgabe klar kennzeichnet (z. B. "Stufe 3 mit ChatGPT-Unterstützung gelöst"). 
