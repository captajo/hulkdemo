# Hulkdemo - Library Search
demo for content search with elasticsearch with memcache for search caching.

# Pre-requisite
The following are required to run the application on your local machine

	1. PHP verison ^7.1
	2. ElasticSearch (Running preferably on port: 9200)
	3. Memcached (Running preferably on port: 11211)
	4. MySQL
	5. Composer 
	6. Git

# Installation using docker

To install the application follow the steps below

1. Clone this repository

	- git clone https://github.com/captajo/hulkdemo.git

2. Install necessary third-party plugins

	From the root directory of this project, open your terminal

		- composer install

3. Generate new application key
	
	From your root directory, open your terminal

		- php artisan key:generate

4. From the root directory of this project, open your terminal and run the following command

		- docker-compose up

5. Launch your browser

	Open your browser and enter `localhost:8000` in your browser url

# Installation on local machine
To install the application follow the steps below

1. Clone this repository

	- git clone https://github.com/captajo/hulkdemo.git

2. Install necessary third-party plugins

	From the root directory of this project, open your terminal

		- composer install

3. Configure your environment variables

	Edit and update the `.env` file located in the root directory of the project, with the following information on your MySQL Database

		- DB_HOST=127.0.0.1
		- DB_PORT=3306
		- DB_DATABASE=<place-your-db-name-here>
		- DB_USERNAME=<place-your-db-username-here>
		- DB_PASSWORD=<place-your-db-password-here>

	[OPTIONAL] Edit and update the `.env` file, with information on your Elasticsearch

		- ELASTICSEARCH_HOST <elasticsearch-host>
		- ELASTICSEARCH_PORT <elasticsearch-port>
		- ELASTICSEARCH_SCHEME http

4. Generate new application key
	
	From your root directory, open your terminal

		- php artisan key:generate

5. Run your application

	From your root directory, open your terminal

		- php artisan serve


6. Launch your browser

	Open your browser and enter `localhost:8000` in your browser url


# Library Features

	- Indexing/Re-indexing of library
	- Indexing only latest updates
	- Indexing search - based on relevance
	- Record updating
	- Search Mis-spell suggestions


# HOW TO USE


# Indexing Your Library Elasticsearch index

To index/reindex your application

1. Open your browser and enter `localhost:8000/` in your browser url

2. On the application configuration page, click on `Start/Re-Index` Button on the library configuration


# Indexing Latest Library Updates in Elasticsearch index

To index the latest changes from your MySQL Database into your Elasticsearch index

1. Open your browser and enter `localhost:8000/` in your browser url

2. On the application configuration page, click on `Index Latest` Button on the library configuration


# Searching Library Index

To search through your index library, follow the steps below

1. Open your browser and enter `localhost:8000/search` in your browser url

2. Enter the `search` term in the search bar provided, to begin searching


# Updating your Library Records

To update your library records

1. Open your browser and enter `localhost:8000/update` in your browser url

2. Select the field type you wish to update, from the drop-down menu

3. Select the specific record you wish to update from the displayed items and click `Edit` button

4. Complete the displayed form

5. Click on `Save Changes` button 


# Additional Files

	- PHPUnit Test - (folder dir - /tests/Unit)