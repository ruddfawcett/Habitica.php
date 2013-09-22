#HabitRPG_PHP
A PHP class for the HabitRPG API

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/ruddfawcett/HabitRPG_PHP/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

##Supported Functions

### GET
* /api/v1/user - (get user status)
    * `x-api-user: uid`
    * `x-api-key: api token`
* /api/v1/user/tasks
    * `x-api-user: uid`
    * `x-api-key: api token`
    * `type: habit | daily | todo | reward` (optional)
* /api/v1/user/task/:id - (get task)
    * `x-api-user: uid`
    * `x-api-key: api token`

### POST
* /api/v1/user/task - (create new task)
    * `x-api-user: uid`
    * `x-api-key: api token`
    * `type: habit | daily | todo | reward` (required)
    * `text: This is an example title` (required)
    * `completed: false`
    * `value: 0`
    * `note: This is just a simple note`

### PUT
* /api/v1/user/task/:id - (update task)
    * update text
    * `x-api-user: uid`
    * `x-api-key: api token`

##Future Functions

- Whichever API methods become available to HabitRPG!

##Links

Link to [HabitRPG](https://habitrpg.com).  Link to it's awesome [Kickstarter](https://www.kickstarter.com/projects/lefnire/habitrpg-mobile) (which you should totally pledge to).  Link to the [source] (https://github.com/lefnire/habitrpg).
