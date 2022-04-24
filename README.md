# Commission Task (Dove)
***Process***: 
This application parses a csv file and converts all given inputs as Operations. 
Based on configuration set in `./src/Config/app.php`, it calculates commission fee
for given inputs.

### Environment Information
- PHP 7.0
- PhpUnit 6.5+
- Composer for dependency management

### Pre-requisites
- PhpUnit is installed and ready.
- Ensure that input.csv with proper formatted data is present at root.
- Required configurations are set.

### Run Process
- Run following script `php script.php input.csv`, it will output the results in console
- It can calculate commission based on Static Rates set in config or can fetch rates from cloud.
- For running tests, use `./test/Service/CommissionServiceTest`
  file with <code>phpunit</code>
