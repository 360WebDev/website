<?php

namespace App\Forms;

use App\Model\Category;
use App\Status;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\Form;

class UserPostForm extends Form
{
    public function buildForm()
    {
    	$rules = [
    		'name' => ['rules' => 'required|min:5|max:255'],
		];
    	if ($this->getMethod() === 'POST') {
    		// Reset slug validation rules for update
    		$rules['slug'] = ['rules' => 'unique:posts'];
		}
        $this
			->add('name', Field::TEXT, $rules['name'])
			->add('slug', Field::TEXT, $rules['slug'] ?? [])
			->add('content', 'textarea', ['attr' => ['class' => 'form-control'], 'label' => 'Contenu (Markdown)'])
			->add('image', Field::FILE);

        // Entity
		$this->addBefore('image', 'category_id', 'entity', [
			'class'       => Category::class,
			'property'    => 'name',
			'empty_value' => '== Sélectionnez une catégorie ==',
			'label_show'  => false,
			'attr'        => ['class' => 'form-control'],
			'rules'       => 'required'
		]);

		// Display online checkbox only for admin
		if (Auth::user()->isAdmin()) {
			$this->add('online', Field::CHECKBOX, ['label' => 'En ligne ?']);
		}

		$checked = $this->getModel()->status === Status::PENDING;
		$this->add('validated', Field::CHECKBOX,
			['label' => 'Soumettre à la validation ?', 'attr' => ['checked' => $checked]]
		);

		$this->add('submit', 'submit', [
			'label' => 'Enregistrer',
			'attr'  => ['class' => 'btn btn-primary']
		]);
    }
}
