<?php

namespace App\Forms\Admin;

use App\Model\Category;
use App\Model\Post;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\Field;
use ReflectionException;

/**
 * Class PostsForm
 *
 * Manage the admin form of the articles.
 */
class PostsForm extends AdminForm
{

	/**
	 * @var string
	 */
    protected $routePrefixName = 'posts';

	/**
	 * @return mixed|void
	 * @throws ReflectionException
	 */
    public function buildForm()
    {
        parent::buildForm();

        /** @var $post Post */
        $post = $this->getModel();
        $user_id = $post->user()->first() ? $post->user()->first()->id : Auth::user()->id;

        // Classic input
        $this
            ->add('name')
            ->add('slug')
            ->add('image', Field::IMAGE)
            ->add('image_file', Field::FILE)
            ->add('content', Field::TEXTAREA, ['attr' => ['id' => 'mdeditor']])
            ->add('online', Field::CHECKBOX)
            ->add('user_id',Field::HIDDEN, ['value' => $user_id]);

        // Entity
        $this->addBefore('image', 'category_id', 'entity', [
            'class'       => Category::class,
            'property'    => 'name',
            'empty_value' => '== Sélectionnez une catégorie ==',
            'label_show'  => false,
            'attr'        => ['class' => 'browser-default'],
            'rules'       => 'required'
        ]);

        $this->add('status', Field::SELECT, [
        	'choices'     => $post->getStatus(),
			'empty_value' => '== Sélectionnez un statut ==',
			'label_show'  => false,
			'attr'        => ['class' => 'browser-default'],

		]);

        $this->add('submit', 'submit', [
            'label' => $this->label,
            'attr'  => ['class' => 'btn btn waves-effect waves-light']
        ]);
        array_merge($this->formOptions, ['file' => true]);
    }
}
