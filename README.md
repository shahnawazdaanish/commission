# Commission Task (Dove)
***Process***: Using input.csv file, parsing all rows and converting it to list Operation Class.
Using reference of a variable processing all commission rate based on configuration. Added custom charge rule for processing special rates.

### Environment Information
- PHP 7.0
- PhpUnit 6.5+
- Composer for dependency management

### Pre-requisites
- PhpUnit is installed and ready.
- Ensure that input.csv with proper formatted data is present at root.
- All required currencies are added to <code>Model\Currency</code>.

### Run Process
- Run following script <code>php script.php input.csv</code>, it will output the results in console
- For configuring rates, <code>Config/Rate.php</code> file is present to reconfigure
- Special rate rule for private withdraw operation is available to be configured at <code>Model\ChargeRule\PrivateWithdrawRule.php</code>.
- For running tests, use <code>phpunit</code>
