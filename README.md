# Dairy Unit Management System
![Yemen Market Home](https://osamazayed.com/images/portfolio-4.webp)
## Overview

The Dairy Unit Management System is a comprehensive platform developed for managing milk collection and distribution operations in collaboration with the General Corporation for Agricultural Services.

### Key Features

- **Milk Collection Application**: 
  - Enables dairy associations to register milk-producing families and track the number of cows per farmer. Documents milk supply operations according to specific storage procedures and provides detailed operational reports.

- **Association Management Application**: 
  - Allows associations to manage branches, record quantities of milk collected and transferred to factories, and manage truck drivers. Generates comprehensive reports on collection and transfer operations.

- **Factory Delegate Application**: 
  - Enables delegates to record milk receipts and perform quality checks. Includes documentation of spoiled quantities and generates accurate reports for institutions and associations.

- **Enterprise Control Panel**: 
  - Provides an integrated management interface for monitoring all system operations with reporting and statistical analysis capabilities.

## Requirements

- **PHP Version**: 
  - PHP >= 8.2

## Installation

To install the Dairy Unit Management System project, follow these steps:

1. Clone the project repository from GitHub:
   ```bash
   git clone https://github.com/osama-zayed/Dairy-Unit-Management.git
   ```

2. Navigate to the downloaded project folder:
   ```bash
   cd Dairy-Unit-Management
   ```

3. Run the database migration:
   ```bash
   php artisan migrate
   ```

4. Seed the default data into the database:
   ```bash
   php artisan db:seed
   ```

5. Start the local development server:
   ```bash
   php artisan serve
   ```

After starting the server, you can access the project through your browser at `http://localhost:8000`.

### Login Information

- **Username**: admin
- **Password**: 123123123
