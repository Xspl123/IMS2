<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecryptionController;   
use App\Http\Controllers\ExportController;
use App\Http\Controllers\CRM\InvoiceController;
use App\Http\Controllers\CRM\WalletController;
//Route::get('/getBarcodeValue', [DecryptionController::class, 'getBarcodeValue']);
Route::get('/export/products', [ExportController::class, 'exportProducts'])->name('export.products');
Route::get('/saleInvoice', [InvoiceController::class, 'index'])->name('saleInvoice');;
Route::get('/invoice/{invoiceId}', [InvoiceController::class, 'showInvoice'])->name('invoice.show');

Route::get('login', 'CRM\AdminController@showLoginForm')->name('login');
Route::post('login/process', 'CRM\AdminController@processLoginAdmin')->name('login/process');
Route::get('logout', 'CRM\AdminController@logout')->name('logout');
Route::get('password/reset', 'CRM\AdminController@renderChangePasswordView')->name('password-reset');
Route::post('password/reset/process', 'CRM\AdminController@processChangePassword')->name('password-process');

Route::get('/', 'DashboardController@index')->name('home');
Route::get('/', 'DashboardController@index');

Route::get('/reload-info', 'DashboardController@processReloadInformation')->name('reload-info');

Route::group(['prefix' => 'clients'], function () {
    Route::get('/', 'CRM\ClientController@processListOfClients')->name('clients');
    Route::get('form/create', 'CRM\ClientController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\ClientController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\ClientController@processShowClientDetails')->name('viewClientDetails');
    Route::post('store', 'CRM\ClientController@processStoreClient')->name('processStoreClient');
    Route::put('update/{clientId}', 'CRM\ClientController@processUpdateClient')->name('processUpdateClient');
    Route::delete('delete/{clientId}', 'CRM\ClientController@processDeleteClient')->name('processDeleteClient');
    Route::get('set-active/{id}/{value}', 'CRM\ClientController@processClientSetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'companies'], function () {
    Route::get('/', 'CRM\CompaniesController@processListOfCompanies')->name('companies');
    Route::get('form/create', 'CRM\CompaniesController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\CompaniesController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\CompaniesController@processViewCompanyDetails')->name('viewCompaniesDetails');
    Route::post('store', 'CRM\CompaniesController@processStoreCompany')->name('processStoreCompanies');
    Route::put('update/{employeeId}', 'CRM\CompaniesController@processUpdateCompany')->name('processUpdateCompanies');
    Route::delete('delete/{clientId}', 'CRM\CompaniesController@processDeleteCompany')->name('processDeleteCompanies');
    Route::get('set-active/{id}/{value}', 'CRM\CompaniesController@processCompanySetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'vendors'], function () {
    Route::get('/', 'CRM\VendorsController@processListOfVendors')->name('vendors');
    Route::get('form/create', 'CRM\VendorsController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\VendorsController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\VendorsController@processViewVendorDetails')->name('viewVendorsDetails');
    Route::post('store', 'CRM\VendorsController@processStoreVendor')->name('processStoreVendors');
    Route::put('update/{employeeId}', 'CRM\VendorsController@processUpdateVendor')->name('processUpdateVendors');
    Route::delete('delete/{clientId}', 'CRM\VendorsController@processDeleteVendor')->name('processDeleteVendors');
    Route::get('set-active/{id}/{value}', 'CRM\VendorsController@processVendorSetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'deals'], function () {
    Route::get('/', 'CRM\DealsController@processListOfDeals')->name('deals');
    Route::get('form/create', 'CRM\DealsController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\DealsController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\DealsController@processShowDealsDetails')->name('viewDealsDetails');
    Route::post('store', 'CRM\DealsController@processStoreDeal')->name('processStoreDeal');
    Route::put('update/{employeeId}', 'CRM\DealsController@processUpdateDeal')->name('processUpdateDeal');
    Route::delete('delete/{clientId}', 'CRM\DealsController@processDeleteDeal')->name('processDeleteDeal');
    Route::get('set-active/{id}/{value}', 'CRM\DealsController@processSetIsActive')->name('processSetIsActive');
    Route::post('store-terms', 'CRM\DealsController@processStoreDealTerms')->name('processStoreDealTerms');
    Route::post('terms/generate-pdf', 'CRM\DealsController@processGenerateDealTermsInPDF');
    Route::delete('terms/delete', 'CRM\DealsController@processDeleteDealTerm')->name('processDeleteDealTerm');
});

Route::group(['prefix' => 'employees'], function () {
    Route::get('/', 'CRM\EmployeesController@processListOfEmployees')->name('employees');
    Route::get('form/create', 'CRM\EmployeesController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\EmployeesController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\EmployeesController@processShowEmployeeDetails')->name('viewEmployeeDetails');
    Route::post('store', 'CRM\EmployeesController@processStoreEmployee')->name('processStoreEmployee');
    Route::put('update/{employeeId}', 'CRM\EmployeesController@processUpdateEmployee')->name('processUpdateEmployee');
    Route::delete('delete/{clientId}', 'CRM\EmployeesController@processDeleteEmployee')->name('processDeleteEmployee');
    Route::get('set-active/{id}/{value}', 'CRM\EmployeesController@processEmployeeSetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/', 'CRM\TasksController@processListOfTasks')->name('tasks');
    Route::get('form/create', 'CRM\TasksController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\TasksController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\TasksController@processShowTasksDetails')->name('viewTasksDetails');
    Route::post('store', 'CRM\TasksController@processStoreTask')->name('processStoreTask');
    Route::put('update/{employeeId}', 'CRM\TasksController@processUpdateTask')->name('processUpdateTask');
    Route::delete('delete/{clientId}', 'CRM\TasksController@processDeleteTask')->name('processDeleteTask');
    Route::get('set-active/{id}/{value}', 'CRM\TasksController@processTaskSetIsActive')->name('processSetIsActive');
    Route::get('/completed/{id}/{value}', 'CRM\TasksController@processSetTaskToCompleted')->name('completeTask');
});

Route::group(['prefix' => 'sales'], function () {
    Route::get('/', 'CRM\SalesController@processListOfSales')->name('sales');
    Route::get('form/create', 'CRM\SalesController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\SalesController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\SalesController@processShowSalesDetails')->name('viewSalesDetails');
    Route::get('challan', 'CRM\SalesController@viewChallansDetails')->name('viewChallansDetails');
    Route::get('challan-details', 'CRM\SalesController@saleChallanDetails')->name('saleChallanDetails');
    Route::get('challan-invoice/{id}', 'CRM\SalesController@challanInvoice')->name('challanInvoice');

    Route::get('challan-invoice-mail/{id}', 'CRM\SalesController@sendmailInvoice')->name('sendmailInvoice');
    Route::get('sendmailChallan/', 'CRM\SalesController@sendmailChallan')->name('sendmailChallan');

    Route::post('store', 'CRM\SalesController@processStoreSale')->name('processStoreSale');
    Route::put('update/{employeeId}', 'CRM\SalesController@processUpdateSale')->name('processUpdateSale');
    Route::delete('delete/{clientId}', 'CRM\SalesController@processDeleteSale')->name('processDeleteSale');
    Route::get('set-active/{id}/{value}', 'CRM\SalesController@processSaleSetIsActive')->name('processSetIsActive');
    Route::get('invoice/{id}', 'CRM\SalesController@showInvoice');
    Route::get('showReplaceItem', 'CRM\SalesController@showReplaceItem')->name('showReplaceItem');
    Route::post('getProducts', 'CRM\SalesController@getProductName')->name('getProductName');
    Route::get('saleCreateChallan/{id}', 'CRM\SalesController@saleChallanCreate')->name('saleChallanCreate');
    Route::get('productUpdate/{id}', 'CRM\SalesController@productUpdate')->name('productUpdate');


});

Route::group(['prefix' => 'rents'], function () {
    Route::get('/', 'CRM\RentsController@processListOfRents')->name('rents');
    Route::get('form/create', 'CRM\RentsController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\RentsController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\RentsController@processShowRentsDetails')->name('viewRentsDetails');
    Route::post('store', 'CRM\RentsController@processStoreRent')->name('processStoreRent');
    Route::put('update/{employeeId}', 'CRM\RentsController@processUpdateRent')->name('processUpdateRent');
    Route::delete('delete/{clientId}', 'CRM\RentsController@processDeleteRent')->name('processDeleteRent');
    Route::get('set-active/{id}/{value}', 'CRM\RentsController@processRentSetIsActive')->name('processSetIsActive');
    Route::get('invoice/{id}', 'CRM\RentsController@showInvoice');




});

Route::group(['prefix' => 'purchase'], function () {
    Route::get('/purchase', 'CRM\PurchaseController@processListOfPurchase')->name('purchase');
    Route::get('form/create', 'CRM\PurchaseController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\PurchaseController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\PurchaseController@processShowPurchaseDetails')->name('viewSalesDetails');
    Route::post('store', 'CRM\PurchaseController@processStorePurchase')->name('processStorePurchase');
    Route::put('update/{employeeId}', 'CRM\PurchaseController@processUpdatePurchase')->name('processUpdatePurchase');
    Route::delete('delete/{clientId}', 'CRM\PurchaseController@processDeletePurchase')->name('processDeletePurchase');
    Route::get('set-active/{id}/{value}', 'CRM\PurchaseController@processPurchaseSetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'CRM\ProductsController@processListOfProducts')->name('products');
    Route::get('form/create', 'CRM\ProductsController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\ProductsController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\ProductsController@processShowProductsDetails')->name('viewProductsDetails');
    Route::post('store', 'CRM\ProductsController@processStoreProduct')->name('processStoreProduct');
    Route::put('update/{employeeId}', 'CRM\ProductsController@processUpdateProduct')->name('processUpdateProduct');
    Route::delete('delete/{clientId}', 'CRM\ProductsController@processDeleteProduct')->name('processDeleteProduct');
    Route::get('set-active/{id}/{value}', 'CRM\ProductsController@processProductSetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'finances'], function () {
    Route::get('/', 'CRM\FinancesController@processListOfFinances')->name('finances');
    Route::get('form/create', 'CRM\FinancesController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\FinancesController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\FinancesController@processShowFinancesDetails')->name('viewFinancesDetails');
    Route::post('store', 'CRM\FinancesController@processStoreFinance')->name('processStoreFinance');
    Route::put('update/{employeeId}', 'CRM\FinancesController@processUpdateFinance')->name('processUpdateFinance');
    Route::delete('delete/{clientId}', 'CRM\FinancesController@processDeleteFinance')->name('processDeleteFinance');
    Route::get('set-active/{id}/{value}', 'CRM\FinancesController@processFinanceSetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'accounts'], function () {
    Route::get('/', 'CRM\AccountsController@processListOfAccounts')->name('accounts');
    Route::get('form/create', 'CRM\AccountsController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\AccountsController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\AccountsController@processShowAccountsDetails')->name('viewAccountsDetails');
    Route::post('store', 'CRM\AccountsController@processStoreAccount')->name('processStoreAccount');
    Route::put('update/{employeeId}', 'CRM\AccountsController@processUpdateAccount')->name('processUpdateAccount');
    Route::delete('delete/{clientId}', 'CRM\AccountsController@processDeleteAccount')->name('processDeleteAccount');
    Route::get('set-active/{id}/{value}', 'CRM\AccountsController@processAccountsetIsActive')->name('processSetIsActive');
});

Route::group(['prefix' => 'transactions'], function () {
    Route::get('/', 'CRM\TransactionsController@processListOfTransactions')->name('transactions');
    Route::get('form/create', 'CRM\TransactionsController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\TransactionsController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\TransactionsController@processShowTransactionsDetails')->name('viewTransactionsDetails');
    Route::post('processStorIncome', 'CRM\TransactionsController@processStoreIncome')->name('processStoreIncome');
    Route::put('update/{employeeId}', 'CRM\TransactionsController@processUpdateTransaction')->name('processUpdateTransaction');
    Route::delete('delete/{clientId}', 'CRM\TransactionsController@processDeleteTransaction')->name('processDeleteTransaction');
    Route::get('set-active/{id}/{value}', 'CRM\TransactionsController@processTransactionsetIsActive')->name('processSetIsActive');
    Route::post('store', 'CRM\TransactionsController@processStoreExpence')->name('processStoreExpence');
    Route::get('expence-create', 'CRM\TransactionsController@processRenderCreateExpenceForm')->name('processRenderCreateExpenceForm');
    Route::post('store', 'CRM\TransactionsController@processStoreExpense')->name('processStoreExpense');
    Route::get('/income-report', 'CRM\TransactionsController@incomeReport')->name('income.report');
    Route::get('/expence-report', 'CRM\TransactionsController@expenceReport')->name('expence.report');
    Route::get('/incomeVsExpense', 'CRM\TransactionsController@incomeVsExpense')->name('incomeVsExpense.report');
    Route::get('/income-report-between-dates/generate', 'CRM\TransactionsController@incomeReportBetweenDates')->name('income.report.between.dates');
});

Route::group(['prefix' => 'daybooks'], function () {
    Route::get('/', 'CRM\DaybooksController@processListOfDaybooks')->name('daybooks');
    Route::get('form/create', 'CRM\DaybooksController@processRenderCreateForm')->name('processRenderCreateForm');
    Route::get('form/update/{clientId}', 'CRM\DaybooksController@processRenderUpdateForm')->name('processRenderUpdateForm');
    Route::get('view/{clientId}', 'CRM\DaybooksController@processShowDaybooksDetails')->name('viewDaybooksDetails');
    // Route::post('processStorIncome', 'CRM\DaybooksController@processStoreIncome')->name('processStoreIncome');
    Route::put('update/{employeeId}', 'CRM\DaybooksController@processUpdateDaybook')->name('processUpdateDaybook');
    Route::delete('delete/{clientId}', 'CRM\DaybooksController@processDeleteDaybook')->name('processDeleteDaybook');
    Route::get('set-active/{id}/{value}', 'CRM\DaybooksController@processDaybooksetIsActive')->name('processSetIsActive');
    Route::post('store', 'CRM\DaybooksController@processStoreExpence')->name('processStoreExpence');
    Route::get('expence-create', 'CRM\DaybooksController@processRenderCreateExpenceForm')->name('processRenderCreateExpenceForm');
    Route::post('store', 'CRM\DaybooksController@processStoreExpense')->name('processStoreExpense');
    Route::get('/income-report', 'CRM\DaybooksController@incomeReport')->name('income.report');
    Route::get('/expence-report', 'CRM\DaybooksController@expenceReport')->name('expence.report');
    Route::get('/incomeVsExpense', 'CRM\DaybooksController@incomeVsExpense')->name('incomeVsExpense.report');
    Route::get('/income-report-between-dates/generate', 'CRM\DaybooksController@incomeReportBetweenDates')->name('income.report.between.dates');
});
Route::post('/update-wallet', 'CRM\WalletController@updateWallet')->name('update.wallet');

Route::get('/getDetails', 'CRM\WalletController@getDetails');

Route::group(['prefix' => 'settings'], function () {
    Route::get('/', 'CRM\SettingsController@processListOfSettings')->name('settings');
    Route::put('update', 'CRM\SettingsController@processUpdateSettings')->name('processUpdateSettings');
});

Route::get('/challanCompanyPDF', 'CRM\SalesController@downloadCompanyPDF')->name('download.com');
Route::get('/challanCustomerPDF', 'CRM\SalesController@downloadCustomerPDF')->name('download.cust');
Route::get('/challanDownloadCompanyPDF', 'CRM\SalesController@challanDownloadCompanyPDF')->name('challanDownloadCompanyPDF');
Route::get('/challanDownloadCustomerPDF', 'CRM\SalesController@challanDownloadCustomerPDF')->name('challanDownloadCustomerPDF');