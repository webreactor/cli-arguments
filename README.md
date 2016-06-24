# cli-arguments

```

use Reactor\CliArguments\ArgumentDefinition;
use Reactor\CliArguments\ArgumentsParser;

$arguments = new ArgumentsParser($argv);
$arguments->addDefinition(new ArgumentDefinition('file', 'f', 'docker-compose.yml', false, false, 'Alternative config file'));
$arguments->addDefinition(new ArgumentDefinition('apps', 'a', 'apps', false, false, 'apps folder realtive to the compose file'));
print_r($arguments->getAll());


```