<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeDtoCommand extends Command
{
    protected $signature = 'make:dto {name}';
    protected $description = 'Create a new dto file';

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
            $this->error("Dto already exists!");
            return;
        }

        $this->makeDirectory($path);

        $stub = $this->getStub();
        $stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);

        $this->files->put($path, $stub);
        $this->info("Dto created successfully.");
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst('App\\', '', $name);
        return app_path('Dtos') . '/' . str_replace('\\', '/', $name) . '.php';
    }

    protected function getClassName($name)
    {
        return Str::afterLast($name, '/');
    }

    protected function getNamespace($name)
    {
        $namespace = str_replace('/', '\\', trim(Str::replaceFirst(app_path(), 'App', app_path('Dtos') . '/' . $name)));
        return Str::beforeLast($namespace, '\\' . $this->getClassName($name));
    }

    protected function getStub()
    {
        return <<<PHP
<?php

namespace DummyNamespace;

use App\Dtos\DtoBase;

class DummyClass extends DtoBase
{
    public function __construct(
        // Add your public properties here
    ) { }

    protected static function create(array \$data): static {
        return new self(
            // Add your properties here
        );
    }

    public static function rules(): array {
        return [
            // Add your validation rules here
        ];
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
