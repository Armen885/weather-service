# Development Setup Instructions

- Create a `.env` file by copying the `.env.example`.
- Add the email configuration details in `.env` file (you can get them from Mailtrap).
- Add the Weatherbit API key https://www.weatherbit.io/api/weather-current (you can use the one provided in `.env.example`).


- Navigate to the project directory.
- Configure a shell alias to execute Sail commands by running:
```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

- Run project using Sail
```bash
sail up
```

- Install Dependencies
```bash
sail composer install
```

- Run migrations to create tables in database
```bash
sail artisan migrate
```

- Open web browser and go to `http://127.0.0.1/` to access the application.
- Click on "Register" to create a new user.

- Run scheduled tasks to send email notifications. It should be configured on the server to run automatically every day.
```bash
sail artisan schedule:run
```

- Or you can run command directly
```bash
sail artisan check:weather
```

- Go to Mailtrap and check your inbox.

- Run tests
```bash
sail artisan test
```
