# ocr-new

Our web-based 'Bank Statement OCR Analysis' project utilizes a PHP-enabled linux web server and mysql-like database server, together with several command-line utilities, e.g. imagemagick, tesseract, php-gd. We combine a basic Laravel PHP framework with jetstream and livewire frameworks, to achieve authentication and user/team functionality. 

Our server configuration is designed to run in cloud-based or on-premise environments, of which the latter is recommended, because of CPU requirements and speed / cost requirements in cloud-based environments. OCR and image processing are very intensive processes, which require performant CPU power for reasonable results.

We utilize a simple route-based API, which can get invoked via the front-end by uploading a PDF bank statement. The main 'job' API call runs through all the steps in succession, although each sub-step may be run seperately via the API too.

The exact API steps, in order, are:

1. PDF to Image

2. Bank Template

3. Header Information

4. Pages to Columns

5. OCR to Data

6. Recon

7. Opening/Closing Balances

8. Detect Salary/Income







