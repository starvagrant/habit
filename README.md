### PHP Hack Night Proposal

The following is an explanation of a simple program that could be improved. It is a simple reminder program for encouraging good habits. I offer it as a coding kata, basic practice on a simple program, for the hack night. To work on it:

1. The code is in a github repo, available as a tarball, and can be seen from a URL

	- **git clone https://github.com/starvagrant/habit**
	- **wget starvagrant.com/habit/hacknight.tar.gz**
	- **http:/starvagrant.com/habit/habitPage.php**

2. Dependencies. This code has been written / tested with the following environment:
	- OS: Ubuntu 14.04
	- Webserver: Apache 2.4.7
	- PHP Version: 5.5.9
	- Database: Sqlite 3.8.2
	- Browser: Firefox 45.0
	- Jquery 1.11.0

The code does not take advantage of bleeding edge features of any of these programs, but dependencies are a tad unpredictable. If you really want practice on resolving dependency issues, be my guest, but if the code isn't up and running in 5 minutes, you might consider a different project. Note the sqlite database is .ht.habit.sqlite and is in the repo. The database needs to be in the folder specified by the dsn, (line 19 of habitPage.php as of this writing) which is an absolute path. If your local environment shows a blank page you may have to change it to reflect your webroot.

### Program Explanation

This is a simple reminder program to develop better habits. It is intended to be a personal home page, the first thing I see when I open a browser. The program lists a set of habits I'm committed to improving that is color coded. The more time that has elapsed since a habit was performed, the more "urgent" the intended color should be.

### Code Implementation

The program is a web based form, with PHP processing the data. The front end has a few features intended to make the program more user friendly, these are implemented with Ajax and Jquery. CSS gradients are involved in the color scheme.

### Improvement Suggestions

The following are areas where the program might be improved.

- **UX**. Since the program uses a web based form, there are opportunities for form enhancement, particularly Javascript / Ajax
- **Refactoring**. I attempted to make my code clean, but it suffers from a consistent problem: the program has a lot of error checking functionality. I think that should be moved outside the program logic.
- **Testing**. A fundamental part of refactoring. All the checks are inside the code itself. A true test-driven implementation is lacking because I don't really understand how to do it.
- **Algorithms.** Currently, the logic is quite simple. Habit is checked based on the timestamp it was last performed. It reacts entirely based on how much time has passed. At some point the program detects that you're not trying, in which case it stops reminding you.

#### Details

1. **UX**: A primary requirement for a good habit program is that it should not be annoying to the user. Having used habit checking programs in the past, I know a primary sin is making it too intrusive. An intrusive program makes the user tempted to ignore the program, and thus put off its suggestions. This is a non-starter. If the user faces too much temptation to stop using the program it is a **FAILURE**. How could this program be made better to use? For instance

	- Does the color scheme actually draw the users eye to the habits in most need? How might the CSS be changed?
	- Is the reminder system uninstrusive? The program implements form improvements via Ajax. Click a button, habit disappears from the list (instead of requiring the user to go through an entire form, the user can check off habits one by one). What other form enhancements could be implemented?
	- No browser testing has been done. It's a quite usable on the latest version of Firefox on the desktop. The actual code was not written "mobile first". However, there is currently no logic or feature that would prevent it from being a web based app for a phone. You can try it on your phone at starvagrant.com/habit.php

2. **Refactoring**: as stated above, the code has a lot of error checking mechanisms that make it less readable. If you enjoyed John Kary's previous talk on functions, this would be a place for you to practice, as I intend on making this program's functions simpler.

3. **Testing**: confused about Unit Testing? Some am I. I'm wanting to add a testing framework for when I add features to the program. I'd like to write the tests first, but writing tests is a skill that has to be learned through practice.

4. **Algorithms**: This program implements some very simple algorithms, basically, a habit's rated urgency goes up twice a day until it reaches a certain threshold. The reasoning is simple: if you're not in the habit of doing something for three days, you're not really in a habit. But there are certain things you do once a week, or once a month. How might the program accomodate that? (It's an improvement for the future).
