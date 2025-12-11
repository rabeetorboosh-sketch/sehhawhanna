<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetMovementController;
use App\Http\Controllers\ControlUnitController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerRequestController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HousingAssignmentController;
use App\Http\Controllers\HousingUnitController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MainInsertionController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\MaintenanceSolutionController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingUnitsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Reports\AssetsMovementsReportsController;
use App\Http\Controllers\Reports\CustomersRequestsReportController;
use App\Http\Controllers\Reports\MonitoringReportController;
use App\Http\Controllers\Reports\RatingsReportsController;
use App\Http\Controllers\Reports\ReportReportingController;
use App\Http\Controllers\Reports\StoreMovementsReportsController;
use App\Http\Controllers\Reports\SuperviseReportController;
use App\Http\Controllers\Reports\TaskReportController;
use App\Http\Controllers\SalesRoutController;
use App\Http\Controllers\ShortsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreMovementsController;
use App\Http\Controllers\SuperviseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskReceiptController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\SubGroup;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth', 'checkPermissions:general-issues,can_show'])->group(function () {
        Route::get('/issuesType', [MainInsertionController::class, 'IssueTypeIndex'])->name('issuesType.index');

        Route::middleware(['auth', 'checkPermissions:general-issues,can_create'])->group(function () {
            Route::get('/issuesType/add', [MainInsertionController::class, 'IssueTypeAdd'])->name('issuesType.add');
            Route::post('/issuesType/create', [MainInsertionController::class, 'IssueTypeCreate'])->name('issuesType.create');
        });

        Route::middleware(['auth', 'checkPermissions:general-issues,can_update'])->group(function () {
            Route::get('/issuesType/edit/{issueType}', [MainInsertionController::class, 'IssueTypeEdit'])->name('issuesType.edit');
            Route::post('/issuesType/update', [MainInsertionController::class, 'IssueTypeUpdate'])->name('issuesType.update');
        });

        Route::middleware(['auth', 'checkPermissions:general-issues,can_delete'])->group(function () {
            Route::delete('/issuesType/delete/{issueType}', [MainInsertionController::class, 'IssueTypeDelete'])->name('issuesType.delete');
        });
    }); //-///-/-//-/-/-/-/-/--/-/--/

    Route::get('/mainGroup/index/{department?}', [MainInsertionController::class, 'MainGroupIndex'])->name('mainGroup.index');
    Route::get('/mainGroup/add/{department?}', [MainInsertionController::class, 'MainGroupAdd'])->name('mainGroup.add');
    Route::post('/mainGroup/crate', [MainInsertionController::class, 'MainGroupCreate'])->name('mainGroup.create');
    Route::get('/mainGroup/edit/{mainGroup}/{department?}', [MainInsertionController::class, 'MainGroupEdit'])->name('mainGroup.edit');
    Route::post('/mainGroup/update/{mainGroup}', [MainInsertionController::class, 'MainGroupUpdate'])->name('mainGroup.update');
    Route::delete('/mainGroup/delete/{mainGroup}/{department?}', [MainInsertionController::class, 'MainGroupDelete'])->name('mainGroup.delete');
    Route::get('/maingroups/{department}', [MainInsertionController::class, 'byDepartment']);

    //-///-/-//-/-/-/-/-/--/-/--/

    Route::get('/subGroup/index/{department?}', [MainInsertionController::class, 'SubGroupIndex'])->name('subGroup.index');
    Route::get('/subGroup/add/{department?}', [MainInsertionController::class, 'SubGroupAdd'])->name('subGroup.add');
    Route::post('/subGroup/crate', [MainInsertionController::class, 'SubGroupCreate'])->name('subGroup.create');
    Route::get('/subGroup/edit/{subGroup}/{department?}', [MainInsertionController::class, 'SubGroupEdit'])->name('subGroup.edit');
    Route::post('/subGroup/update/{subGroup}', [MainInsertionController::class, 'SubGroupUpdate'])->name('subGroup.update');
    Route::delete('/subGroup/delete/{subGroup}/{department?}', [MainInsertionController::class, 'SubGroupDelete'])->name('subGroup.delete');
    Route::middleware(['auth', 'checkPermissions:2-insertions-assets,can_show'])->group(function () {
        Route::get('/assets', [AssetController::class, 'index'])->name('asset.index');
        Route::middleware(['auth', 'checkPermissions:2-insertions-assets,can_update'])->group(function () {
            Route::get('/assets/add', [AssetController::class, 'add'])->name('asset.add');
            Route::post('/assets/store', [AssetController::class, 'create'])->name('asset.create');
        });
        Route::middleware(['auth', 'checkPermissions:2-insertions-assets,can_update'])->group(function () {
            Route::get('/assets/edit/{id}', [AssetController::class, 'edit'])->name('asset.edit');
            Route::post('/assets/update/{asset}', [AssetController::class, 'update'])->name('asset.update');
        });
        Route::get('/assets/{id}', [AssetController::class, 'show'])->name('asset.show');
        Route::middleware(['auth', 'checkPermissions:2-insertions-assets,can_delete'])->group(function () {
            Route::delete('/assets/delete/{id}', [AssetController::class, 'destroy'])->name('asset.delete');
        });
    });
    Route::middleware(['auth', 'checkPermissions:1-insertions-units,can_show'])->group(function () {
        Route::get('/units', [UnitController::class, 'index'])->name('units.index');

        Route::middleware(['auth', 'checkPermissions:1-insertions-units,can_create'])->group(function () {
            Route::get('/units/add', [UnitController::class, 'create'])->name('units.add');
            Route::post('/units/store', [UnitController::class, 'store'])->name('units.store');
        });

        Route::middleware(['auth', 'checkPermissions:1-insertions-units,can_update'])->group(function () {
            Route::get('/units/edit/{id}', [UnitController::class, 'edit'])->name('units.edit');
            Route::put('/units/update/{id}', [UnitController::class, 'update'])->name('units.update');
        });

        Route::middleware(['auth', 'checkPermissions:1-insertions-units,can_delete'])->group(function () {
            Route::delete('/units/delete/{id}', [UnitController::class, 'destroy'])->name('units.delete');
        });
    });
    //---/-/-----/-/-/--/-/-----/-----/-/--/-/-/-/-/--//--/-/--//
    Route::middleware(['auth', 'checkPermissions:8-insertions-customers,can_show'])->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

        Route::middleware(['auth', 'checkPermissions:8-insertions-customers,can_create'])->group(function () {
            Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
            Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        });

        Route::middleware(['auth', 'checkPermissions:8-insertions-customers,can_update'])->group(function () {
            Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
            Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        });

        Route::middleware(['auth', 'checkPermissions:8-insertions-customers,can_delete'])->group(function () {
            Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        });
    });
    ///-/-/--/-/--/-/-/--/-/-/-/-/-------------------------------///-/
    //-/
    Route::prefix('employees')->middleware(['auth', 'checkPermissions:4-insertions-employees,can_show'])->group(function() {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');

        Route::middleware(['auth', 'checkPermissions:4-insertions-employees,can_create'])->group(function () {
            Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
            Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store');
        });

        Route::middleware(['auth', 'checkPermissions:4-insertions-employees,can_update'])->group(function () {
            Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
            Route::put('/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        });

        Route::middleware(['auth', 'checkPermissions:4-insertions-employees,can_delete'])->group(function () {
            Route::delete('/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:9-insertions-suppliers,can_show'])->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

        Route::middleware(['auth', 'checkPermissions:9-insertions-suppliers,can_create'])->group(function () {
            Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
            Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        });
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

        Route::middleware(['auth', 'checkPermissions:9-insertions-suppliers,can_update'])->group(function () {
            Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
            Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        });

        Route::middleware(['auth', 'checkPermissions:9-insertions-suppliers,can_delete'])->group(function () {
            Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:1-insertions-products,can_show'])->group(function () {
        Route::get('/items', [ItemController::class, 'index'])->name('items.index');

        Route::middleware(['auth', 'checkPermissions:1-insertions-products,can_create'])->group(function () {
            Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
            Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        });
        Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

        Route::middleware(['auth', 'checkPermissions:1-insertions-products,can_update'])->group(function () {
            Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
            Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        });

        Route::middleware(['auth', 'checkPermissions:1-insertions-products,can_delete'])->group(function () {
            Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        });
    });
    Route::get('controlUnit/department/{department?}', [ControlUnitController::class, 'index'])->name('controlUnit.index');
    Route::get('controlUnit/create/{department?}', [ControlUnitController::class, 'create'])->name('controlUnit.create');
    Route::post('controlUnit', [ControlUnitController::class, 'store'])->name('controlUnit.store');
    Route::get('/subgroups/{mainGroup}', function ($mainGroupId) {
        return SubGroup::where('main_group_id', $mainGroupId)->get();
    });
    Route::get('controlUnit/{controlUnit}', [ControlUnitController::class, 'show'])->name('controlUnit.show');
    Route::get('controlUnit/{controlUnit}/edit/{department?}', [ControlUnitController::class, 'edit'])->name('controlUnit.edit');
    Route::put('controlUnit/{controlUnit}', [ControlUnitController::class, 'update'])->name('controlUnit.update');
    Route::delete('controlUnit/{controlUnit}/{department?}', [ControlUnitController::class, 'destroy'])->name('controlUnit.destroy');
    Route::middleware(['auth', 'checkPermissions:1-insertions-stores,can_show'])->group(function () {
        Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');

        Route::middleware(['auth', 'checkPermissions:1-insertions-stores,can_create'])->group(function () {
            Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
            Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
        });
        Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');

        Route::middleware(['auth', 'checkPermissions:1-insertions-stores,can_update'])->group(function () {
            Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
            Route::put('/stores/{store}', [StoreController::class, 'update'])->name('stores.update');
        });

        Route::middleware(['auth', 'checkPermissions:1-insertions-stores,can_delete'])->group(function () {
            Route::delete('/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
        });
    });

    Route::get('/myTasks', [TaskAssignmentController::class, 'myTasks'])->name('myTask.index');
    Route::middleware(['auth', 'checkPermissions:5-insertions-tasks,can_show'])->group(function () {
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::middleware(['auth', 'checkPermissions:5-insertions-tasks,can_create'])->group(function () {
            Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
            Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        });



        Route::middleware(['auth', 'checkPermissions:5-insertions-tasks,can_update'])->group(function () {
            Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
            Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        });

        Route::middleware(['auth', 'checkPermissions:5-insertions-tasks,can_delete'])->group(function () {
            Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        });
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/index/{department?}', [ReportController::class, 'index'])->name('index');
        Route::get('/create/{department?}', [ReportController::class, 'create'])->name('create');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::get('/{report}/edit/{department?}', [ReportController::class, 'edit'])->name('edit');
        Route::put('/{report}', [ReportController::class, 'update'])->name('update');
        Route::delete('/{report}/{department}', [ReportController::class, 'destroy'])->name('destroy');
    });
    Route::middleware(['auth', 'checkPermissions:daily_monitoring-daily_monitoring,can_show'])->group(function () {
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

        Route::middleware(['auth', 'checkPermissions:daily_monitoring-daily_monitoring,can_create'])->group(function () {
            Route::get('/monitoring/create', [MonitoringController::class, 'create'])->name('monitoring.create');
            Route::get('/monitoring/part_create/{dep_id?}', [MonitoringController::class, 'partCreate'])->name('monitoring.partCreate');
            Route::post('/monitoring', [MonitoringController::class, 'store'])->name('monitoring.store');
        });
        Route::get('/monitoring/{monitoring}', [MonitoringController::class, 'show'])->name('monitoring.show');

        Route::middleware(['auth', 'checkPermissions:daily_monitoring-daily_monitoring,can_update'])->group(function () {
            Route::get('/monitoring/{monitoring}/edit', [MonitoringController::class, 'edit'])->name('monitoring.edit');
            Route::put('/monitoring/{monitoring}', [MonitoringController::class, 'update'])->name('monitoring.update');
        });

        Route::middleware(['auth', 'checkPermissions:daily_monitoring-daily_monitoring,can_delete'])->group(function () {
            Route::delete('/monitoring/{monitoring}', [MonitoringController::class, 'destroy'])->name('monitoring.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:5-operations-assignments,can_show'])->group(function () {
        Route::get('/task_assignments', [TaskAssignmentController::class, 'index'])->name('task_assignments.index');

        Route::middleware(['auth', 'checkPermissions:5-operations-assignments,can_create'])->group(function () {
            Route::get('/task_assignments/create', [TaskAssignmentController::class, 'create'])->name('task_assignments.create');
            Route::post('/task_assignments', [TaskAssignmentController::class, 'store'])->name('task_assignments.store');
            Route::post('/task_assign/assign', [TaskAssignmentController::class, 'assign'])->name('taskAssignment.assign');
        });
        Route::get('/task_assignments/task_list', [TaskAssignmentController::class, 'list'])->name('task_assignments.list');
        Route::get('/task_assignments/{task_assignment}', [TaskAssignmentController::class, 'show'])->name('task_assignments.show');

        Route::middleware(['auth', 'checkPermissions:5-operations-assignments,can_update'])->group(function () {
            Route::get('/task_assignments/{task_assignment}/edit', [TaskAssignmentController::class, 'edit'])->name('task_assignments.edit');
            Route::put('/task_assignments/{task_assignment}', [TaskAssignmentController::class, 'update'])->name('task_assignments.update');
        });

        Route::middleware(['auth', 'checkPermissions:5-operations-assignments,can_delete'])->group(function () {
            Route::delete('/task_assignments/{task_assignment}', [TaskAssignmentController::class, 'destroy'])->name('task_assignments.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:5-operations-receipts,can_show'])->group(function () {
        Route::get('/task_receipts', [TaskReceiptController::class, 'index'])->name('task_receipts.index');

        Route::middleware(['auth', 'checkPermissions:5-operations-receipts,can_create'])->group(function () {
            Route::get('/task_receipts/create', [TaskReceiptController::class, 'create'])->name('task_receipts.create');
            Route::post('/task_receipts', [TaskReceiptController::class, 'store'])->name('task_receipts.store');
            Route::post('/task_receipts/rate/{task_receipt}', [TaskReceiptController::class, 'rate'])->name('task_receipts.rate');
        });
        Route::get('/task_receipts/receipts/{id}/{occurrences?}', [TaskReceiptController::class, 'receipt'])->name('task_assignments.receipt');

        Route::get('/task_receipts/{task_receipt}', [TaskReceiptController::class, 'show'])->name('task_receipts.show');

        Route::middleware(['auth', 'checkPermissions:5-operations-receipts,can_update'])->group(function () {
            Route::get('/task_receipts/{task_receipt}/edit', [TaskReceiptController::class, 'edit'])->name('task_receipts.edit');
            Route::put('/task_receipts/{task_receipt}', [TaskReceiptController::class, 'update'])->name('task_receipts.update');
        });

        Route::middleware(['auth', 'checkPermissions:5-operations-receipts,can_delete'])->group(function () {
            Route::delete('/task_receipts/{task_receipt}', [TaskReceiptController::class, 'destroy'])->name('task_receipts.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:2-operations-maintenance_request,can_show'])->group(function () {
        Route::get('/maintenance_requests', [MaintenanceRequestController::class, 'index'])->name('maintenance_requests.index');

        Route::middleware(['auth', 'checkPermissions:2-operations-maintenance_request,can_create'])->group(function () {
            Route::get('/maintenance_requests/create', [MaintenanceRequestController::class, 'create'])->name('maintenance_requests.create');
            Route::post('/maintenance_requests', [MaintenanceRequestController::class, 'store'])->name('maintenance_requests.store');
        });
        Route::get('/maintenance_requests/{maintenance_request}', [MaintenanceRequestController::class, 'show'])->name('maintenance_requests.show');

        Route::middleware(['auth', 'checkPermissions:2-operations-maintenance_request,can_update'])->group(function () {
            Route::get('/maintenance_requests/{maintenance_request}/edit', [MaintenanceRequestController::class, 'edit'])->name('maintenance_requests.edit');
            Route::put('/maintenance_requests/{maintenance_request}', [MaintenanceRequestController::class, 'update'])->name('maintenance_requests.update');
            Route::get('/maintenance_requests/approve/{request?}', [MaintenanceRequestController::class, 'approve'])
                ->name('maintenance_requests.approve');
        });
        Route::middleware(['auth', 'checkPermissions:2-operations-maintenance_request,can_delete'])->group(function () {
            Route::delete('/maintenance_requests/{maintenance_request}', [MaintenanceRequestController::class, 'destroy'])->name('maintenance_requests.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:2-operations-maintenance,can_show'])->group(function () {
        Route::get('/maintenance_solutions', [MaintenanceSolutionController::class, 'index'])->name('maintenance_solutions.index');

        Route::middleware(['auth', 'checkPermissions:2-operations-maintenance,can_create'])->group(function () {
            Route::get('/maintenance_solutions/create/{request?}', [MaintenanceSolutionController::class, 'create'])
                ->name('maintenance_solutions.create');
            Route::post('/maintenance_solutions', [MaintenanceSolutionController::class, 'store'])->name('maintenance_solutions.store');
        });
        Route::get('/maintenance_solutions/{maintenance_solution}', [MaintenanceSolutionController::class, 'show'])->name('maintenance_solutions.show');

        Route::middleware(['auth', 'checkPermissions:2-operations-maintenance,can_update'])->group(function () {
            Route::get('/maintenance_solutions/{maintenance_solution}/edit', [MaintenanceSolutionController::class, 'edit'])->name('maintenance_solutions.edit');
            Route::put('/maintenance_solutions/{maintenance_solution}', [MaintenanceSolutionController::class, 'update'])->name('maintenance_solutions.update');
        });

        Route::middleware(['auth', 'checkPermissions:2-operations-maintenance,can_delete'])->group(function () {
            Route::delete('/maintenance_solutions/{maintenance_solution}', [MaintenanceSolutionController::class, 'destroy'])->name('maintenance_solutions.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:8-operations-supervises,can_show'])->group(function () {
        Route::get('/supervises', [SuperviseController::class, 'index'])->name('supervises.index');

        Route::middleware(['auth', 'checkPermissions:8-operations-supervises,can_create'])->group(function () {
            Route::get('/supervises/create', [SuperviseController::class, 'create'])->name('supervises.create');
            Route::post('/supervises', [SuperviseController::class, 'store'])->name('supervises.store');
        });
        Route::get('/supervises/{supervise}', [SuperviseController::class, 'show'])->name('supervises.show');

        Route::middleware(['auth', 'checkPermissions:8-operations-supervises,can_update'])->group(function () {
            Route::get('/supervises/{supervise}/edit', [SuperviseController::class, 'edit'])->name('supervises.edit');
            Route::put('/supervises/{supervise}', [SuperviseController::class, 'update'])->name('supervises.update');
        });

        Route::middleware(['auth', 'checkPermissions:8-operations-supervises,can_delete'])->group(function () {
            Route::delete('/supervises/{supervise}', [SuperviseController::class, 'destroy'])->name('supervises.destroy');
        });
    });
    Route::middleware(['auth', 'checkPermissions:2-operations-movements,can_show'])->group(function () {
        Route::get('/asset_movements', [AssetMovementController::class, 'index'])->name('asset_movements.index');

        Route::middleware(['auth', 'checkPermissions:2-operations-movements,can_create'])->group(function () {
            Route::get('/asset_movements/create', [AssetMovementController::class, 'create'])->name('asset_movements.create');
            Route::post('/asset_movements', [AssetMovementController::class, 'store'])->name('asset_movements.store');
        });
        Route::get('/asset_movements/{asset_movement}', [AssetMovementController::class, 'show'])->name('asset_movements.show');

        Route::middleware(['auth', 'checkPermissions:2-operations-movements,can_update'])->group(function () {
            Route::get('/asset_movements/{asset_movement}/edit', [AssetMovementController::class, 'edit'])->name('asset_movements.edit');
            Route::put('/asset_movements/{asset_movement}', [AssetMovementController::class, 'update'])->name('asset_movements.update');
        });

        Route::middleware(['auth', 'checkPermissions:2-operations-movements,can_delete'])->group(function () {
            Route::delete('/asset_movements/{asset_movement}', [AssetMovementController::class, 'destroy'])->name('asset_movements.destroy');
        });
    });






    Route::get('/sales_routs', [SalesRoutController::class, 'index'])->name('sales_routs.index');
    Route::get('/sales_routs/create', [SalesRoutController::class, 'create'])->name('sales_routs.create');
    Route::post('/sales_routs', [SalesRoutController::class, 'store'])->name('sales_routs.store');
    Route::get('/sales_routs/{sales_rout}', [SalesRoutController::class, 'show'])->name('sales_routs.show');
    Route::get('/sales_routs/{sales_rout}/edit', [SalesRoutController::class, 'edit'])->name('sales_routs.edit');
    Route::put('/sales_routs/{sales_rout}', [SalesRoutController::class, 'update'])->name('sales_routs.update');
    Route::delete('/sales_routs/{sales_rout}', [SalesRoutController::class, 'destroy'])->name('sales_routs.destroy');




    Route::resource('users', UserController::class);
    Route::get('users/permit/{user}', [UserController::class, 'permit'])->name('users.permit');

    Route::resource('permissions', PermissionsController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('templates', TemplateController::class);
    Route::post('/shorts', [ShortsController::class, 'store'])->name('shorts.store');
    Route::delete('/shorts', [ShortsController::class, 'delete'])->name('shorts.delete');



     Route::get('requests', [CustomerRequestController::class, 'index'])->name('customersRequests.index');
     Route::get('requests/create', [CustomerRequestController::class, 'create'])->name('customersRequests.create');
     Route::post('requests', [CustomerRequestController::class, 'store'])->name('customersRequests.store');
     Route::post('requests/change/{id}', [CustomerRequestController::class, 'changStatus'])->name('customersRequests.changStatus');
     Route::get('requests/{id}', [CustomerRequestController::class, 'show'])->name('customersRequests.show');
     Route::get('requests/{id}/edit', [CustomerRequestController::class, 'edit'])->name('customersRequests.edit');
     Route::put('requests/{id}', [CustomerRequestController::class, 'update'])->name('customersRequests.update');
     Route::delete('requests/{id}', [CustomerRequestController::class, 'destroy'])->name('customersRequests.destroy');





    Route::get('report/monitoring', [MonitoringReportController::class,'index'])->name('reportMonitoring.index');
    Route::get('report/tasks', [TaskReportController::class, 'index'])->name('reportTasks.index');
    Route::get('report/tasks/empSummary', [TaskReportController::class, 'byEmployeeSummary'])->name('reportTasks.byEmployeeSummary');
    Route::get('report/tasks/empDetail/{employee}', [TaskReportController::class, 'byEmployeeDetail'])->name('reportTasks.byEmployeeDetail');
    Route::get('report/supervises', [SuperviseReportController::class, 'index'])->name('reportSupervisors.index');
    Route::get('report/supervises/byUserDetail', [SuperviseReportController::class, 'byUserDetail'])->name('reportSupervisors.byUserDetail');
    Route::get('report/movements/byOperation/detail/{id?}', [StoreMovementsReportsController::class, 'byOperationDetail'])->name('storeMovements.byOperationDetail');
    Route::get('report/movements/byStore/detail', [StoreMovementsReportsController::class, 'byStoreDetail'])->name('storeMovements.byStoreDetail');
    Route::get('report/movements/byProduct/detail/{id?}', [StoreMovementsReportsController::class, 'byProductDetail'])->name('storeMovements.byProductDetail');
    Route::get('report/movements/byEmployee/detail', [StoreMovementsReportsController::class, 'byEmployeeDetail'])->name('storeMovements.byEmployeeDetail');
    Route::get('report/movements/bySubGroup/detail', [StoreMovementsReportsController::class, 'bySubGroupDetail'])->name('storeMovements.bySubGroupDetail');
    Route::get('report/movements/index', [StoreMovementsReportsController::class, 'index'])->name('storeMovements.ReportIndex');
    Route::get('report/assetsMovements/byOperation', [AssetsMovementsReportsController::class, 'byOperation'])->name('assetsMovements.byOperation');
    Route::get('report/ratingReport/byOperation', [RatingsReportsController::class, 'byOperationDetail'])->name('ratingReport.byOperationDetail');
    Route::get('report/alertReport', [ReportReportingController::class, 'index'])->name('ratingReport.index');


    Route::get('report/customerRequest', [CustomersRequestsReportController::class, 'index'])->name('customerRequests.index');
    Route::get('report/customerRequest/byEmployee/detail', [CustomersRequestsReportController::class, 'byEmployeeDetail'])->name('customerRequests.byEmployeeDetail');
    Route::get('report/customerRequest/byProduct/detail', [CustomersRequestsReportController::class, 'byProductDetail'])->name('customerRequests.byProductDetail');
    Route::get('report/customerRequest/byOperation/detail', [CustomersRequestsReportController::class, 'byOperationDetail'])->name('customerRequests.byOperationDetail');
    Route::get('report/customerRequest/byCustomer/detail', [CustomersRequestsReportController::class, 'byCustomerDetail'])->name('customerRequests.byCustomerDetail');
    Route::get('report/SystemMovement', [MainController::class, 'SystemMovementIndex'])->name('SystemMovement.index');



    Route::get('report/monitoring/print', [MonitoringReportController::class,'print'])->name('reportMonitoring.print');
    Route::get('report/tasks/print', [TaskReportController::class,'print'])->name('reportTasks.print');
    Route::get('report/supervises/print', [SuperviseReportController::class,'print'])->name('reportSupervisors.print');
    Route::get('report/supervises/print/byUserDetail', [SuperviseReportController::class, 'byUserDetailPrint'])->name('reportSupervisors.byUserDetailPrint');
    Route::get('report/movements/print/byOperation/detail/{id?}', [StoreMovementsReportsController::class, 'byOperationDetailPrint'])->name('storeMovements.byOperationDetailPrint');
    Route::get('report/movements/print/byStore/detail', [StoreMovementsReportsController::class, 'byStoreDetailPrint'])->name('storeMovements.byStoreDetailPrint');
    Route::get('report/movements/print/byProduct/detail/{id?}', [StoreMovementsReportsController::class, 'byProductDetailPrint'])->name('storeMovements.byProductDetailPrint');
    Route::get('report/movements/print/byEmployee/detail', [StoreMovementsReportsController::class, 'byEmployeeDetailPrint'])->name('storeMovements.byEmployeeDetailPrint');
    Route::get('report/movements/print/bySubGroup/detail', [StoreMovementsReportsController::class, 'bySubGroupDetailPrint'])->name('storeMovements.bySubGroupDetailPrint');

    Route::get('report/assetsMovements/print/byOperation', [AssetsMovementsReportsController::class, 'byOperationPrint'])->name('assetsMovements.byOperationPrint');
    Route::get('report/ratingReport/print/byOperation', [RatingsReportsController::class, 'byOperationDetailPrint'])->name('ratingReport.byOperationDetailPrint');


    Route::get('report/customerRequests/print/byEmployee/detail', [CustomersRequestsReportController::class, 'byEmployeeDetailPrint'])->name('customerRequests.byEmployeeDetailPrint');
    Route::get('report/customerRequests/print/byProduct/detail', [CustomersRequestsReportController::class, 'byProductDetailPrint'])->name('customerRequests.byProductDetailPrint');
    Route::get('report/customerRequests/print/byOperation/detail', [CustomersRequestsReportController::class, 'byOperationDetailPrint'])->name('customerRequests.byOperationDetailPrint');
    Route::get('report/customerRequests/print/byCustomer/detail', [CustomersRequestsReportController::class, 'byCustomerDetailPrint'])->name('customerRequests.byCustomerDetailPrint');

    Route::get('store-movements/create/{movement?}', [StoreMovementsController::class, 'create'])
        ->name('storeMovements.create');
    Route::get('store-movements/operation/{movement?}', [StoreMovementsController::class, 'index'])
        ->name('storeMovements.index');
    Route::post('store-movements/store', [StoreMovementsController::class, 'store'])
        ->name('storeMovements.store');
    Route::get('store-movements/view/{id}', [StoreMovementsController::class, 'show'])
        ->name('storeMovements.show');
    Route::get('store-movements/edit/{id}', [StoreMovementsController::class, 'edit'])
        ->name('storeMovements.edit');
    Route::put('store-movements/update/{id}', [StoreMovementsController::class, 'update'])
        ->name('storeMovements.update');
    Route::delete('store-movements/delete/{id}', [StoreMovementsController::class, 'destroy'])
        ->name('storeMovements.destroy');
    Route::resource('rating_units', RatingUnitsController::class);
    Route::get('ratings/filter/{id?}', [App\Http\Controllers\RatingController::class, 'index'])
        ->name('ratings.filter');
    Route::resource('ratings', RatingController::class);
    Route::resource('housing_units', HousingUnitController::class);
    Route::resource('housing_assignments', HousingAssignmentController::class);
    Route::get('/housing/rooms/{unit_id}', [HousingAssignmentController::class, 'getRooms']);
    Route::get('/housing/room-status/{id}', [HousingAssignmentController::class, 'roomStatus']);



    Route::get('/employeeTypes', [MainInsertionController::class, 'EmployeeTypeIndex'])->name('employeeType.index');
    Route::get('/employeeTypes/add', [MainInsertionController::class, 'EmployeeTypeAdd'])->name('employeeType.add');
    Route::post('/employeeTypes/create', [MainInsertionController::class, 'EmployeeTypeCreate'])->name('employeeType.create');
    Route::get('/employeeTypes/edit/{employeeType}', [MainInsertionController::class, 'EmployeeTypeEdit'])->name('employeeType.edit');
    Route::post('/employeeTypes/update', [MainInsertionController::class, 'EmployeeTypeUpdate'])->name('employeeType.update');
    Route::get('/employeeTypes/{id}', [MainInsertionController::class, 'EmployeeTypeShow'])->name('employeeType.show');
    Route::delete('/employeeTypes/delete/{id}', [MainInsertionController::class, 'EmployeeTypeDelete'])->name('employeeType.delete');


});

require __DIR__.'/auth.php';


Route::get('/tester', [MainController::class, 'tester'])->name('tester');
