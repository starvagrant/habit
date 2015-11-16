### Road Map

Determine Habits for Reinforcment
Determine Algorithms for Points
Put Shell Scripts in Cronjobs 

### Habit Program, Goals

- To enforce good habits
- To give myself a schedule
- to setup a rewards systems for good habits

### Rewards

The reward system is key. It is so easy to fall behind on what you're doing. Sleeping in, listening a bit too much to entertainment, visiting too many websites, too much time on facebook etc. So I'd like something like a point system that I can stick to. Ideas:

- All personal spending be earned through points. This would include books, cds, etc. I could buy them with enough points.
- Time. All personal time also to be earned through points. Time to work on novel, extend vim, etc. etc. On days other than Sunday, which I have reserved. Admittedly this sounds too draconian, and I'd much better manage my time through working on spending.

### Points System

I score points based on habits I'd like to reinforce. During a testing month, I think I should get $40 for computer books or $15 for cds and $25 for play parties (for myself), or one computer book a month. 

### Scheduler

In some ways my habit program should give me a schedule suggestion, based on time. I want hourly, daily, weekly, and monthly tasks. 

### Program Map

I'm looking for a basic shell script for now. Something I could use with cron. The program could work on a daily, weekly, and monthly basis, and give me a list of tasks it would prompt me to do. It would give me points based on what I got done, though first I think I should just setup something in my cron tab. It would

Every day, it would add entries to my cron tab. The script would read those entries and ask me at the prompt if I'd done them yes or no. If I answered yes, the program would delete that entry and give me a score. If I answered no the program would keep the entry their and I'd lose points.

I think scores should be calculated by the following means: scores for habits should increase arithmatically, and penalties geometrically. So, for instance, let's say I have a daily habit that should score me 8 points. When I don't do it, it would score me 2 points, and increase. So here's the math: I do a habit daily for a week: 7 * 8 = 56. I miss three days in a row. -2 -4 -8 + 8 = -6. I miss a habit 6 day in a row: -2 -4 -8 -16 -32 -64 8 = -118 or -255 points. How about every other day? -2 8 -2 8 -2 8 -2 = 16. 

I think I could tweak my algorithms with unit testing, but I would have to keep track of how much I earned with any given habit.
