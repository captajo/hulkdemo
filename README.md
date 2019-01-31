# hulkdemo
demo for content search with elasticsearch with memcache for search caching.

# Pre-requisit
The following are required to run the application on your local machine

	1. PHP verison ^7.1
	2. ElasticSearch (Running preferably on port: 9200)
	3. Memcached (Running preferably on port: 11211)
	4. MySQL
	5. Composer 
	6. Git

# Installation
To install the application follow the steps below

1. Clone this repository

	- git clone https://github.com/captajo/hulkdemo.git

2. Install necessary third-party plugins

	From the root directory of this project, open your terminal

		- composer install

3. Configure your environment variables

	Edit and update the `.env` file located in the root directory of the project, with the following information

		- DB_HOST=127.0.0.1
		- DB_PORT=3306
		- DB_DATABASE=<place-your-db-name-here>
		- DB_USERNAME=<place-your-db-username-here>
		- DB_PASSWORD=<place-your-db-password-here>

	

