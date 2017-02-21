# cli-arguments

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/webreactor/cli-arguments.git"
        }
    ],
    "require": {
        "webreactor/cli-arguments": "v0.0.2",
        "symfony/yaml": "v2.7.3"
    }
}
```

use Reactor\CliArguments\ArgumentDefinition;
use Reactor\CliArguments\ArgumentsParser;

$arguments = new ArgumentsParser($argv);
$arguments->addDefinition(new ArgumentDefinition('file', 'f', 'docker-compose.yml', false, false, 'Alternative config file'));
$arguments->addDefinition(new ArgumentDefinition('apps', 'a', 'apps', false, false, 'apps folder realtive to the compose file'));
print_r($arguments->getAll());


```
