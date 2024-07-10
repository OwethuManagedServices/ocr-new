# ocr-new

Our web-based project utilizes a PHP-enabled linux web server and mysql-like database server, together with several command-line utilities, e.g. imagemagick, tesseract, php-gd. We combine the basic Laravel PHP framework with jetstream and livewire frameworks, to achieve authentication and user/team functionality. It should be made clear that this solution 

Our server configuration is designed to run in cloud-based or on-premise environments, of which the latter is recommended, because of CPU requirements and speed / cost requirements in cloud-based environments.

We utilize a simple route-based API, which can get invoked via the front-end by uploading a PDF bank statement. The main 'job' API call runs through all the steps in succession, although each sub-step may be run seperately via the API too.

The exact API steps, in order, are:
PDF to Image
Bank Template
Header Information
Pages to Columns
OCR to Data
Recon
Opening/Closing Balances
Detect Salary






