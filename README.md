#Habitica PHP
A PHP class for the new Habitica API V2

##Supported Functions

### GET
* User status
* User tasks
* Task id by task name/text
* Task information by task id

### POST
* Create New Tasks
    * `type: habit | daily | todo | reward`
    * `text: This is an example title` 
    * `value: 0` 
    * `note: This is just a simple note` 
* Score Tasks
    * `taskId: some-task-id`
    * `direction: up | down`
* Update Tasks
    * `taskId: some-task-id`
    * `text: Updated title`
    
### PUT
* Update Tasks
    * 'text: Updated title' (required)
