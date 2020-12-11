<?php

// PackageManager::load('admin-default')
//    ->css('extend', public_path('packages/sleepingowl/default/css/extend.css'));

use App\Models\CommunityTheme;
use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Region;
use App\Models\Profile\Relationship;
use App\Models\Rbac\Permission;
use App\Models\Rbac\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(User::class, static function (ModelConfiguration $model) {
    $model->setTitle('Users');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::datatables()
            ->setColumns([
                AdminColumn::link('id')->setLabel('ID')->setWidth('250px'),
                AdminColumn::image('profile.user_pic', 'User Pic')->setImageWidth('50px'),
                AdminColumn::text('profile.first_name')->setLabel('Name'),
                AdminColumn::text('profile.last_name')->setLabel('Last Name'),
                AdminColumn::text('email')->setLabel('Email'),
                AdminColumn::custom('is_admin', static function (Model $model) {
                    return (int)$model->is_admin === 1 ? 'Да' : 'Нет';
                })->setLabel('Admin'),
                AdminColumn::url('email', '')
                    ->setIcon('fas fa-reply')
                    ->setView('column.urlint')
                    ->setOrderable(false),
                AdminColumn::url('email', '')
                    ->setIcon('fas fa-reply-all')
                    ->setView('column.urlext')
                    ->setOrderable(false)
            ]);
        $display->getColumnFilters()
            ->setPlacement('table.header')
            ->set([
                AdminColumnFilter::text()->setPlaceholder('ID')->setOperator(FilterInterface::CONTAINS),
                null,
                AdminColumnFilter::text()->setPlaceholder('Name')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Last Name')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Email')->setOperator(FilterInterface::CONTAINS),
                null
            ]);
        $display->with('profile');
        $display->paginate(30);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('email', 'Email'),
            AdminFormElement::checkbox('is_admin', 'Admin')
        );
    });
})
    ->addMenuPage(User::class, 1)
    ->setIcon('fa fa-user')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

AdminSection::registerModel(Relationship::class, static function (ModelConfiguration $model) {
    $model->setTitle('Relationships');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::link('id')->setLabel('ID')->setWidth('400px'),
            AdminColumn::text('title')->setLabel('Title'),
        ]);
        $display->paginate(30);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('title', 'Title')
        );
    });
})
    ->addMenuPage(Relationship::class, 1)
    ->setIcon('fa fa-heartbeat')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

AdminSection::registerModel(Country::class, static function (ModelConfiguration $model) {
    $model->setTitle('Countries');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::datatables()
            ->setColumns([
                AdminColumn::link('id')->setLabel('ID')->setWidth('100px'),
                AdminColumn::text('title_ru')->setLabel('Title RU'),
                AdminColumn::text('title_ua')->setLabel('Title UA'),
                AdminColumn::text('title_en')->setLabel('Title EN'),
            ])
            ->paginate(30);
        $display->getColumnFilters()
            ->setPlacement('table.header')
            ->set([
                AdminColumnFilter::text()->setPlaceholder('ID')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title RU')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title UA')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title EN')->setOperator(FilterInterface::CONTAINS),
            ]);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('title_ru', 'Title RU'),
            AdminFormElement::text('title_ua', 'Title UA'),
            AdminFormElement::text('title_en', 'Title EN')
        );
    });
})
    ->addMenuPage(Country::class, 1)
    ->setIcon('fa fa-globe')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

AdminSection::registerModel(Region::class, static function (ModelConfiguration $model) {
    $model->setTitle('Regions');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::datatables()
            ->setColumns([
                AdminColumn::link('id')->setLabel('ID')->setWidth('100px'),
                AdminColumn::text('country.title_ru')->setLabel('Country RU'),
                AdminColumn::text('title_ru')->setLabel('Title RU'),
                AdminColumn::text('title_ua')->setLabel('Title UA'),
                AdminColumn::text('title_en')->setLabel('Title EN'),
            ])
            ->paginate(30);
        $display->getColumnFilters()
            ->setPlacement('table.header')
            ->set([
                AdminColumnFilter::text()->setPlaceholder('ID')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Country')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title RU')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title UA')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title EN')->setOperator(FilterInterface::CONTAINS),
            ]);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('title_ru', 'Title RU'),
            AdminFormElement::text('title_ua', 'Title UA'),
            AdminFormElement::text('title_en', 'Title EN')
        );
    });
})
    ->addMenuPage(Region::class, 1)
    ->setIcon('fa fa-globe-europe')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

AdminSection::registerModel(City::class, static function (ModelConfiguration $model) {
    $model->setTitle('Cities');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::datatables()
            ->setColumns([
                AdminColumn::link('id')->setLabel('ID')->setWidth('100px'),
                AdminColumn::text('country.title_ru')->setLabel('Country RU'),
                AdminColumn::text('region.title_ru')->setLabel('Region RU'),
                AdminColumn::text('title_ru')->setLabel('Title RU'),
                AdminColumn::text('title_ua')->setLabel('Title UA'),
                AdminColumn::text('title_en')->setLabel('Title EN'),
            ])->paginate(30);
        $display->getColumnFilters()
            ->setPlacement('table.header')
            ->set([
                AdminColumnFilter::text()->setPlaceholder('ID')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Country')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Region')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title RU')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title UA')->setOperator(FilterInterface::CONTAINS),
                AdminColumnFilter::text()->setPlaceholder('Title EN')->setOperator(FilterInterface::CONTAINS),
            ]);

        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('title_ru', 'Title RU'),
            AdminFormElement::text('title_ua', 'Title UA'),
            AdminFormElement::text('title_en', 'Title EN')
        );
    });
})
    ->addMenuPage(City::class, 1)
    ->setIcon('fa fa-globe-asia')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

/**
 * RBAC
 */
AdminSection::registerModel(Permission::class, static function (ModelConfiguration $model) {
    $model->setTitle('RBAC Permitions');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::link('id')->setLabel('ID')->setWidth('100px'),
            AdminColumn::text('name')->setLabel('Name'),
            AdminColumn::text('display_name')->setLabel('Display name'),
            AdminColumn::text('description')->setLabel('Description'),
        ]);
        $display->paginate(30);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Name'),
            AdminFormElement::text('display_name', 'Display name'),
            AdminFormElement::text('description', 'Description')
        );
    });
})
    ->addMenuPage(Permission::class, 1)
    ->setIcon('fa fa-viruses')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

AdminSection::registerModel(Role::class, static function (ModelConfiguration $model) {
    $model->setTitle('RBAC Role');
    // Display
    $model->onDisplay(static function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::link('id')->setLabel('ID')->setWidth('100px'),
            AdminColumn::text('name')->setLabel('Name'),
            AdminColumn::text('display_name')->setLabel('Display name'),
            AdminColumn::text('description')->setLabel('Description'),
            AdminColumn::text('priority')->setLabel('Priority'),
        ]);
        $display->paginate(30);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Name'),
            AdminFormElement::text('display_name', 'Display name'),
            AdminFormElement::text('description', 'Description'),
            AdminFormElement::text('priority', 'Priority')
        );
    });
})
    ->addMenuPage(Role::class, 1)
    ->setIcon('fa fa-pencil-ruler')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });

AdminSection::registerModel(CommunityTheme::class, static function (ModelConfiguration $model) {
    $model->setTitle('Community Themes');
    // Display
    $model->onDisplay(static function () {
        return AdminDisplay::tree()
            ->setValue('name')
            ->setReorderable(false)
            ->setOrderField('name');
    });
    // Create And Edit
    $model->onCreateAndEdit(static function () {
        return AdminForm::card()->addBody(
            AdminFormElement::select('parent_id', 'Parent', CommunityTheme::getParents()->toArray())
                ->setValidationRules([
                    'required'
                ]),
            AdminFormElement::text('name', 'Title')->setValidationRules([
                'required',
                Rule::unique('community_themes', 'name')->where(static function ($query) {
                    return $query->where('parent_id', request()->post('parent_id'));
                })
            ])
        );
    });
})
    ->addMenuPage(CommunityTheme::class, 1)
    ->setIcon('fa fa-tree')
    ->setAccessLogic(static function () {
        $user = auth()->user();
        return $user instanceof User && $user->isAdmin();
    });
