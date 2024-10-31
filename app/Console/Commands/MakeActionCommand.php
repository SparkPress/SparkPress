<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeActionCommand extends Command
{
    protected $signature = 'make:action {name}';
    protected $description = 'Create a new action file';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->getPath($name);

        if ($this->files->exists($path)) {
            $this->error("Action already exists!");
            return;
        }

        $this->makeDirectory($path);

        $stub = $this->getStub();
        $stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);

        $this->files->put($path, $stub);
        $this->info("Action created successfully.");
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst('App\\', '', $name);
        return app_path('Actions') . '/' . str_replace('\\', '/', $name) . '.php';
    }

    protected function getClassName($name)
    {
        return Str::afterLast($name, '/');
    }

    protected function getNamespace($name)
    {
        $namespace = str_replace('/', '\\', trim(Str::replaceFirst(app_path(), 'App', app_path('Actions') . '/' . $name)));
        return Str::beforeLast($namespace, '\\' . $this->getClassName($name));
    }

    protected function getStub()
    {
        return <<<PHP
<?php

namespace DummyNamespace;

use Lorisleiva\Actions\Concerns\AsAction;

class DummyClass
{
    use AsAction;

    public function handle()
    {
        // Add your logic here
    }
}
PHP;
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }
    }
}
