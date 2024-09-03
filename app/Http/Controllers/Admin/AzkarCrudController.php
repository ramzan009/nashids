<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AzkarRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AzkarCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AzkarCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    private string $language_model;
    private string $language_model_fields;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->language_model = 'models/Azkar.';
        $this->language_model_fields = 'models/Azkar.fields.';

        CRUD::setModel(\App\Models\Azkar::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/azkar');
        CRUD::setEntityNameStrings(__($this->language_model . 'entity_name'), __($this->language_model. 'entity_plural_name'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation(): void
    {
        CRUD::addColumns([
            [
                'name' => 'id',
                'label' => __($this->language_model_fields . 'id')
            ],
            [
                'name' => 'title',
                'label' => __($this->language_model_fields . 'title')

            ],
            [
                'name' => 'azkarType',
                'attribute' => 'alias',
                'label' => __($this->language_model_fields . 'azkar_type_id')

            ],
        ]);

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AzkarRequest::class);
        CRUD::addFields([
            [
                'name' => 'title',
                'label' => __($this->language_model_fields . 'title')
            ],
            [
                'name' => 'azkar_type_id',
                'label' => __($this->language_model_fields . 'azkar_type_id'),
            ],
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
