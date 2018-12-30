<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Make command for generate Repository class
 *
 * @example
 * 	php artisan make:repository FooRepository => Generate simple class
 * 	php artisan make:repository FooRepository -m Foo => Generate repository class with and its model, default namespace
 * 	model is App\Model
 */
class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository class';

	/**
	 * @var string
	 */
    protected $type = 'Repository';

	/**
	 * A default namespace model class
	 *
	 * @var string
	 */
    protected $modelNamespace = 'App\\Model\\';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		if ($this->option('model')) {
			return __DIR__ . '/stubs/repository.stub';
		}
		return __DIR__ . '/stubs/repository_plain.stub';
	}

	/**
	 * @param string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace)
	{
		return $rootNamespace . '\Repository';
	}

	/**
	 * @param string $name
	 * @return mixed|string
	 */
	protected function buildClass($name)
	{
		$replace = [];
		if ($this->option('model')) {
			$replace = $this->buildModelReplacements();
		}

		return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
	}

	/**
	 * @return string[][]
	 */
	protected function getOptions()
	{
		return [
			['model', 'm', InputOption::VALUE_OPTIONAL, 'Model of repository class']
		];
	}

	/**
	 * @return string[]
	 */
	private function buildModelReplacements()
	{
		$modelClass = $this->modelNamespace . $this->option('model');

		if (!class_exists($modelClass)) {
			if ($this->confirm(sprintf('A %s model does not exist. Do you want to generate it ?', $modelClass), true)) {
				$this->call('make:model', ['name' => $modelClass]);
			}
		}

		return [
			'DummyFullModelClass'  => $modelClass,
			'DummyShortModelClass' => class_basename($modelClass),
			'dummyModelVariable'   => lcfirst(class_basename($modelClass)),
		];
	}
}
