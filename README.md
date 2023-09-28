# Stock Info Application

Welcome to our Stock Info Application, where we take stock data seriously... but not too seriously! 

This Laravel application lets you dive into historical stock quotes with a touch of humor. You can retrieve stock data, display it in a table, visualize prices, and even get emails with company info. Ready to laugh your way through finance? Let's get started!

## Installation (Buckle Up for Fun)

Follow these steps to set up the application. And remember, it's more fun if you imagine doing it with a clown wig on!

1. Clone the repository to your local machine. Use this command and pretend you're a pirate searching for treasure:

   ```bash
   git clone https://github.com/saquibhasan3/stock-info.git
   ```

2. Navigate to the project directory like a fearless explorer:

   ```bash
   cd stock-info
   ```

3. Install composer dependencies. Imagine each package as a cute pet you're adopting:

   ```bash
   composer install
   ```

4. Create a `.env` file by copying the `.env.example` file. Pretend you're copying a secret code:

   ```bash
   cp .env.example .env
   ```

5. Open the `.env` file, and set your environment variables. Imagine you're teaching your computer to speak your language:

   - `APP_URL`: Set the URL of your application.
   - `MAIL_MAILER`: Set the mailer (e.g., `smtp`).
   - `MAIL_HOST`: Set the mail host (e.g., `smtp.mailtrap.io`).
   - `MAIL_PORT`: Set the mail port (e.g., `587`).
   - `MAIL_USERNAME`: Set your email username.
   - `MAIL_PASSWORD`: Set your email password.
   - `RAPIDAPI_KEY`: Set your RapidAPI key.
   - `RAPIDAPI_HOST`: Set the RapidAPI host (`yh-finance.p.rapidapi.com`).

6. Generate an application key. It's like giving your app a secret handshake:

   ```bash
   php artisan key:generate
   ```

7. Start the development server. Pretend you're starting a party:

   ```bash
   php artisan serve
   ```

8. Access the application in your web browser at `http://localhost:8000`. Pretend your computer is your trusty steed, and you're off to the digital Wild West!

## Usage (Get Ready to Chuckle)

1. Fill out the form with the following fields:

   - Company Symbol
   - Start Date (YYYY-mm-dd)
   - End Date (YYYY-mm-dd)
   - Email

2. Click the "Submit" button. It's like pressing the big red button in a spaceship, but way less dramatic.

3. The application will validate the form inputs on both the client and server side. If there are errors, we'll let you know. Don't worry; no clowns were hurt during validation! 

4. If the form is valid, historical quotes for the submitted Company Symbol within the given date range will be displayed in a table format. It's like a stock market buffet without the financial indigestion.

5. A chart showing the Open and Close prices will also be displayed based on the retrieved historical data. It's data visualization with a dash of pizzazz!

6. An email will be sent to the submitted email address with the following details:

   - Subject: The name of the submitted company (e.g., Google for symbol GOOG). It's like a personalized message from your favorite stock.

   - Body: Start Date and End Date (e.g., From 2020-01-01 to 2020-01-31). We keep it simple, so you don't have to read a novel.

## Dependencies (The Supporting Cast)

- Laravel 10.2.6
- Bootstrap 5.3.2
- Chart.js (for those cool charts)
- DataTables (for our data table)
- Bootstrap Datepicker (for date selection)
- jQuery Validation (to keep those form fields in check)
- Guzzle HTTP Client (for fetching data)

## Credits (We Share the Laughter)

- This project uses data from [RapidAPI](https://rapidapi.com/). We couldn't do it without them!

## License (Legal Stuff, But We're Still Smiling)

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details. Enjoy your stock adventure, and remember, laughter is the best investment! 