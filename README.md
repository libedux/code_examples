# About Project

We are seeking the design of an automatic message-sending system for this project.

The structure designed to send 2 messages every 5 seconds. After a message is sent, the sent status should be marked in the database.
The sending process will return a message ID. During message listing, these IDs are expected to be returned as a JSON response.

Requirements

● Service Layer with Repository Pattern used

● Queue, Job/Worker used

● Laravel Command used to send message

● Redis cache implemented

● Complied with RESTful API standards

## Getting Started

Follow these steps to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

You need to have **Docker** installed on your system to run the application with Laravel Sail.

### Installation

1. **Duplicate the Environment File:**
   First, copy the example environment file to a new `.env` file where you can configure your specific settings. Also DO NOT forget to register WEBHOOK_URL_TOKEN 

2. **Start Sail and Docker Containers:**
    Bring up all the Docker containers required by the application in the background.

    ```bash
    ./vendor/bin/sail up -d
    ```
3. **App Key and migration commands:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail artisan db:seed
    ```

4. **Queue And Command**
    ```bash
    ./vendor/bin/sail artisan queue:work
    ./vendor/bin/sail artisan process:messages
    ```
5. **Test**
    ```bash
    ./vendor/bin/sail phpunit --filter MessageServiceTest
    ```
    
