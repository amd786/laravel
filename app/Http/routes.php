<?php
Route::auth();
//Route::get('/', 'UserController@dashboard');
Route::get('/', 'UserController@welcome');
Route::get('/dashboard', 'UserController@dashboard');

// develoer mode routes
Route::get('developer-mode/{var?}', 'UserController@DeveloperMode');
Route::get('developer-modules-table', 'UserController@DeveloperModulesTable');
Route::get('edit-module/{var?}/{id?}', 'UserController@EditModule');
Route::post('save-module', 'UserController@SaveModule');
Route::post('save-edit-module/{id?}', 'UserController@SaveEditModule');
Route::get('add-module/{var?}', 'UserController@AddModule');
Route::get('delete-module/{var?}/{id?}', 'UserController@DeleteModule');

// admin routes
Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){
  // for Purchase module
    Route::group(['prefix' => 'purchase'], function () {
      Route::get('/', 'PurchaseController@index');
      Route::get('create-purchase-order', 'PurchaseController@CreatePurchaseOrder');
      Route::get('supplier-address-book', 'PurchaseController@SupplierAddressBook');
      Route::get('add-supplier', 'PurchaseController@AddSupplier');
      Route::post('save-supplier', 'PurchaseController@SaveSupplier');
      Route::get('add-supplier-contact', 'PurchaseController@AddSupplierContact');
      Route::post('save-supplier-contact', 'PurchaseController@SaveSupplierContact');
      Route::get('supplier-profile/{id?}', 'PurchaseController@SupplierProfile');
      Route::post('update-supplier', 'PurchaseController@UpdateSupplier');
      Route::post('save-purchase-order', 'PurchaseController@SavePurchaseOrder');
      Route::post('purchase-list', 'PurchaseController@PurchaseList');
      Route::get('choose-supplier', 'PurchaseController@ChooseSupplier');
      Route::get('confirm-order', 'PurchaseController@ConfirmOrder');
      Route::post('save-order', 'PurchaseController@SaveOrder');
      Route::get('order-confirmed/{id?}', 'PurchaseController@OrderConfirmed');
      Route::get('order-approvals', 'PurchaseController@OrderApprovals');
      Route::get('pending-orders', 'PurchaseController@PendingOrders');
      Route::get('approved-orders', 'PurchaseController@ApprovedOrders');
      Route::get('rejected-orders', 'PurchaseController@RejectedOrders');
      Route::post('reject-order', 'PurchaseController@RejectOrder');
      Route::post('approve-order', 'PurchaseController@ApproveOrder');
      Route::get('order-details/{id?}', 'PurchaseController@OrderDetails');
      Route::get('order-fulfillment', 'PurchaseController@OrderFulfillment');
      Route::post('bulk-purchase-list', 'PurchaseController@BulkPurchaseList');
      Route::get('order-history', 'PurchaseController@OrderHistory');
      Route::get('order-by-supplier', 'PurchaseController@OrdersBySupplier');
      Route::post('get-quote-skus', 'PurchaseController@GetQuoteSkus');
      Route::post('is-valid-quote-sku', 'PurchaseController@IsValidQuoteSku');
      Route::get('orders-by-date', 'PurchaseController@OrdersByDate');
      Route::get('order-fulfillment-work-order', 'PurchaseController@OrderFulfillmentWorkOrder');
      Route::get('order-fulfillment-items', 'PurchaseController@OrderFulfillmentItems');
      Route::post('purchase-list-sku', 'PurchaseController@PurchaseListSku');

    });
  // ******************************************************************************************
  // for Inventory module
      Route::group(['prefix' => 'inventory'], function () {
        Route::get('/', 'InventoryController@index');
        Route::get('/view-inventory', 'InventoryController@ViewInventory');
        Route::get('/check-in-out', 'InventoryController@CheckInOut');
        Route::get('/move-inventory', 'InventoryController@MoveInventory');
        Route::get('/track-inventory', 'InventoryController@TrackInventory');
        Route::get('/rent-return', 'InventoryController@RentReturn');
        Route::post('/save-rent-return', 'InventoryController@SaveRentReturn');
        Route::get('/checkout-approval', 'InventoryController@CheckoutApproval');
        Route::post('/save-move-inventory', 'InventoryController@SaveMoveInventory');
        Route::get('/track-inventory-detail/{var?}/', 'InventoryController@TrackInventoryDetail');
        Route::post('/save-manual-add', 'InventoryController@SaveManualAdd');
        Route::get('/delete-manual-add/{var?}/', 'InventoryController@DeleteManualAdd');
        Route::post('/check-in', 'InventoryController@CheckIn');
        Route::post('/is-valid-sku', 'InventoryController@IsValidSku');
        Route::post('/check-out', 'InventoryController@CheckOut');
        Route::post('/change-is-lock-status', 'InventoryController@ChangeIsLockStatus');
        Route::post('/save-inventory-threshold', 'InventoryController@SaveInventoryThreshold');
        Route::get('/raw-materials', 'InventoryController@RawMaterials');
        Route::get('/unit-management', 'InventoryController@UnitManagement');
        Route::post('/save-unit', 'InventoryController@SaveUnit');
        Route::get('/add-raw-material', 'InventoryController@AddRawMaterial');
        Route::post('/save-raw-material', 'InventoryController@SaveRawMaterial');
        Route::get('/check-in-out-raw-materials', 'InventoryController@CheckInOutRawMaterials');
        Route::post('/save-manual-add-raw-material', 'InventoryController@SaveManualAddRawMaterials');
        Route::get('/delete-manual-add-raw-material/{var?}/', 'InventoryController@DeleteManualAddRawMaterial');
        Route::post('/check-in-raw-material', 'InventoryController@CheckInRawMaterial');
        Route::post('/find-check-in-raw-material', 'InventoryController@FindCheckInRawMaterial');
        Route::post('/check-out-raw-material', 'InventoryController@CheckOutRawMaterial');
        Route::get('/view-raw-materials-inventory', 'InventoryController@ViewRawMaterialsInventory');
        Route::post('/change-raw-material-lock-status', 'InventoryController@ChangeRawMaterialLockStatus');
        Route::post('/save-raw-material-threshold', 'InventoryController@SaveRawMaterialThreshold');
        Route::post('/raw-material-skus', 'InventoryController@RawMaterialSkus');
        Route::get('/order-fulfillment', 'InventoryController@OrderFulfillment');
        Route::get('/order-details/{var?}/', 'InventoryController@OrderDetails');
        Route::post('/raw-material-list', 'InventoryController@RawMaterialList');
        Route::post('/inventory-skus', 'InventoryController@InventorySkus');
        Route::post('/raw-material-quantity', 'InventoryController@RawMaterialQuantity');
        Route::post('save-request-manufacture', 'InventoryController@SaveRequestManufacture');
        Route::post('/approval-details', 'InventoryController@ApprovalDetails');
        Route::post('/save-reject-checkout', 'InventoryController@SaveRejectCheckout');
        Route::post('/save-approve-checkout', 'InventoryController@SaveApproveCheckout');
        Route::post('/get-threshold', 'InventoryController@GetThreshold');
        Route::post('/save-scan-sku', 'InventoryController@SaveScanSku');
        Route::get('/get-scanned-itmes', 'InventoryController@GetScannedItems');
        Route::post('/save-marked-delivered', 'InventoryController@SaveMarkDelivered');
        Route::get('/get-lock-unlock-details', 'InventoryController@GetLockUnlockDetails');
        Route::get('/get-lock-unlock-raw-material-details', 'InventoryController@GetLockUnlockRawMaterialDetails');
        Route::post('/get-work-orders', 'InventoryController@GetWorkOrders');
        Route::get('/raw-material/{var?}/','InventoryController@RawMaterial');
        Route::post('save-raw-material-suppliers/{id?}', 'InventoryController@SaveRawMaterialSuppliers');
        Route::post('/save-scan-sku-raw-materials', 'InventoryController@SaveScanSkuRawMaterials');
        Route::get('/get-scanned-raw-materials', 'InventoryController@GetScannedRawMaterials');
        Route::get('/delete-unit/{var?}/','InventoryController@DeleteUnit');
      });
  // ******************************************************************************************
  // for Engineering module
      Route::group(['prefix' => 'engineering'], function () {
        Route::get('/', 'EngineeringController@index');
        Route::get('/seal-documentation', 'EngineeringController@SealDocumentation');
        Route::post('/save-seal-doc', 'EngineeringController@SaveSealDoc');
        Route::get('/on-hold', 'EngineeringController@OnHold');
        Route::get('/on-hold-desc', 'EngineeringController@OnHoldDesc');
        Route::any('/delete-hold-desc/{id?}', 'EngineeringController@DeleteHoldDesc');
        Route::get('/modal-hold-desc', 'EngineeringController@ModalHoldDesc');
        Route::post('/save-hold-desc', 'EngineeringController@SaveHoldDesc');
        Route::get('/order-review', 'EngineeringController@OrderReview');
        Route::any('/ajax-product-class', 'EngineeringController@AjaxProductClass');
        Route::any('/ajax-delete-product-class', 'EngineeringController@AjaxDeleteProductClass');
        Route::any('/engineered-solution', 'EngineeringController@EngineeredSolution');
        Route::any('/get-engineered-solutions-info', 'EngineeringController@GetEngineeredSolutionsInfo');
        Route::any('/get-engineered-solutions-images', 'EngineeringController@GetEngineeredSolutionsImages');
        Route::post('/save-engineered-doc', 'EngineeringController@SaveEngineeredDoc');
        Route::any('/eng-get-order-info', 'EngineeringController@EngGetOrderInfo');
        Route::any('/single-hold-order/{id?}', 'EngineeringController@SingleHoldOrder');
        Route::any('/save-single-hold-order/{id?}', 'EngineeringController@SaveSingleHoldOrder');
        Route::any('/delete-es-files', 'EngineeringController@DeleteEsFiles');
        Route::any('/eng-change-drder-status', 'EngineeringController@EngChangeOrderStatus');
        Route::any('/save-eng-postpone-status', 'EngineeringController@SaveEngPostponeStatus');
      });
  // ******************************************************************************************

  // for user module
      // for see all user module
        Route::group(['prefix' => 'users'], function () {
          Route::get('/', 'UserController@index');
          Route::get('/all-users', 'UserController@AllUser');
          Route::get('/user-detail/{var?}', 'UserController@UserDetail');
          Route::get('/edit-user/{var?}', 'UserController@EditUser');
          Route::get('/delete-user/{var?}', 'UserController@delete');
          Route::post('/save-edit-user/{var?}', 'UserController@SaveEditUser');
          Route::get('/add-new', 'UserController@AddUser');
          Route::post('/save-user', 'UserController@SaveUser');
          Route::get('/all-roles', 'UserController@roles');
          Route::get('/delete-role/{var?}', 'UserController@DeleteRole');
          Route::get('/edit-role/{var?}', 'UserController@EditRole');
          Route::post('/add-role', 'UserController@AddRole');
          Route::post('/save-permission', 'UserController@SavePermission');
          Route::post('/assign-users', 'UserController@AssignUsers');
          Route::any('/profile', 'UserController@EditProfile');
          Route::post('/save-edit-role/{var?}', 'UserController@SaveEditRole');
          Route::get( '/users-table', 'UserController@UsersTable' );
          Route::get('/disable-user/{var?}', 'UserController@DisableUser');
          Route::post('/reset-password/{var?}', 'UserController@ResetPassword');
          Route::post('/role-permission', 'UserController@RolePermission');

          // external users
          Route::get( '/external-users', 'UserController@ExternalUsers' );
          Route::get( '/external-users-table', 'UserController@ExternalUsersTable' );
          Route::get( '/add-client', 'UserController@AddClient' );
          Route::post( '/save-client', 'UserController@SaveClient' );
          Route::post( '/contact-detail', 'UserController@ContactDetail' );


        });
      Route::get('/anyData','UserController@anyData');

  // end user module
  // ******************************************************************************************
  // for crm module
      Route::group(['prefix' => 'crm'], function () {
        Route::get('/', 'CrmController@index');
        Route::get('/add-new-client', 'CrmController@AddClient');
        Route::post('/save-new-client', 'CrmController@SaveClient');
        Route::get('/add-new-contact', 'CrmController@AddContact');
        Route::post('/save-new-contact', 'CrmController@SaveContact');
        Route::get('/addressbook', 'CrmController@ShowAllAddress');
        Route::get('/view-addressbook/{var?}','CrmController@ViewAddressbook');
        Route::post('/save-edit-addressbook/{var?}','CrmController@SaveEditAddressbook');
        Route::get('/delete-addressbook/{var?}','CrmController@DeleteAddressbook');
        Route::get('/view-contact/{var?}','CrmController@ViewContact');
        Route::post('/save-edit-contact/{var?}','CrmController@SaveEditContact');
        Route::get('/price-list-adjustment','CrmController@PriceListAdjustment');
        Route::get('/delete-pla/{var?}','CrmController@DeletePla');
      });
      Route::get('/address-data', 'CrmController@AddressData');
      Route::get('/contact-data/{var?}', 'CrmController@ContactData');
      Route::get('/showpla', 'CrmController@ShowPla');
  // end crm module
  // ******************************************************************************************
  // for product module
    Route::group(['prefix' => 'products'], function () {
      Route::get('/', 'ProductController@index');
      Route::get('/product-class', 'ProductController@ProductClass');
      Route::get('/modal-product-class', 'ProductController@ModalProductClass');
      Route::get('/modal-product-class/{var?}', 'ProductController@ModalProductClass');
      Route::post('/save-product-class', 'ProductController@SaveProductClass');
      Route::post('/save-edit-product-class/{var?}', 'ProductController@SaveEditProductClass');
      Route::get('/delete-product-class/{var?}', 'ProductController@DeleteProductClass');
      Route::get('/product', 'ProductController@Product');
      Route::get('/modal-product', 'ProductController@ModalProduct');
      Route::get('/modal-product/{var?}', 'ProductController@ModalProduct');
      Route::post('/save-product', 'ProductController@SaveProduct');
      Route::post('/save-edit-product/{var?}', 'ProductController@SaveEditProduct');
      Route::get('/delete-product/{var?}', 'ProductController@DeleteProduct');
      Route::get('/attributes', 'ProductController@Attributes');
      Route::post('/save-new-attribute', 'ProductController@SaveNewAttribute');
      Route::get('/modal-add-attribute/{id?}', 'ProductController@ModalAddAttribute');
      Route::get('/edit-attribute/{var?}', 'ProductController@EditAttribute');
      Route::get('/copy-attribute/{var?}', 'ProductController@CopyAttribute');
      Route::post('/copy-attribute-save/{var?}', 'ProductController@CopyAttributeSave');
      Route::get('/delete-attribute/{var?}', 'ProductController@DeleteAttribute');
      Route::get('/modal-attribute-value/{var?}/{var2?}', 'ProductController@ModalAddAttributeValue');
      Route::post('/save-attr-value', 'ProductController@SaveAttrValue');
      Route::post('/save-edit-attr-value/{var?}', 'ProductController@SaveEditAttrValue');
      Route::get('/delete-attr-value/{var?}', 'ProductController@DeleteAttrValue');
      Route::get('/assign-part-numbers', 'ProductController@AssignPartNo');
      Route::get('/assign-part-number/{id?}', 'ProductController@AssignPart');
      Route::get('/modal-attribute-val-sel/{var?}', 'ProductController@ModalAttributeValSel');
      Route::get('/view-all', 'ProductController@ViewAll');
      Route::get('/view-all-details/{var?}', 'ProductController@ViewAllDetails');
      Route::get('price-management', 'ProductController@PriceManagement');
      Route::get('set-product-price', 'ProductController@SetProductPrice');
      Route::get('products-without-approval', 'ProductController@ProductsWithoutApproval');
      Route::get('modal-without-approval/{var?}', 'ProductController@ModalWithoutApproval');
      Route::post('save-price/{var?}', 'ProductController@SavePrice');
      Route::get('products-with-approval', 'ProductController@ProductsWithApproval');
      Route::get('products-with-approval-table', 'ProductController@ProductsWithApprovalTable');
      Route::get('price-view-all', 'ProductController@PriceViewAll');
      Route::get('price-view-all-table', 'ProductController@PriceViewAllTable');
      Route::get('product-class-tolerance', 'ProductController@ProductClassTolerance');
      Route::get('modal-class-tolerance/{var?}', 'ProductController@ModalClassTolerance');
      Route::post('save-class-tolerance/{var?}', 'ProductController@SaveClassTolerance');
      Route::get('product-view-all', 'ProductController@ProductViewAll');
      Route::get('product-view-all-table', 'ProductController@ProductViewAllTable');
      Route::post('importExcel', 'ProductController@importExcel');
      Route::post('product-filter', 'ProductController@ProductFilter');
      Route::get('bom-import', 'ProductController@BomImport');
      Route::post('save-bom-import', 'ProductController@SaveBomImport');
      Route::post('preview-bom', 'ProductController@PreviewBom');
      Route::post('save-raw-material-list','ProductController@SaveRawMaterialList');
      Route::get('clear-assign-parts', 'ProductController@ClearAssignParts');

  });
  Route::any('product-without-appr', 'ProductController@ProductWithoutAppr');
  // for product module
  // ******************************************************************************************
  // for sales module
  Route::group(['prefix' => 'sales'], function () {
    Route::get('/', 'SaleController@index');
    //Route::get('create-quote', 'SaleController@CreateQuote');
    Route::get('quote/{var?}', 'SaleController@CreateQuote');
    Route::post('save-quotes/{var?}', 'SaleController@SaveQuotes');
    Route::get('add-product-modal', 'SaleController@AddProductModal');
    Route::get('sales-report', 'SaleController@SalesReport');
    Route::get('delete-quote-product/{id?}', 'SaleController@DeleteQuoteProduct');
    Route::get('sales-setting', 'SaleController@SalesSetting');
    Route::get('payment-terms', 'SaleController@PaymentTerms');
    Route::get('modal-payment-terms', 'SaleController@ModalPaymentTerms');
    Route::get('delete-payment/{id?}', 'SaleController@DeletePayment');
    Route::post('save-payment-terms', 'SaleController@SavePaymentTerms');
    Route::get('taxes', 'SaleController@Taxes');
    Route::get('modal-tax', 'SaleController@ModalTax');
    Route::get('delete-tax/{id?}', 'SaleController@DeleteTax');
    Route::post('save-Tax', 'SaleController@SaveTax');
    Route::get('excuses', 'SaleController@Excuses');
    Route::get('modal-excuse', 'SaleController@ModalExcuse');
    Route::get('delete-excuse/{id?}', 'SaleController@DeleteExcuse');
    Route::post('save-excuse', 'SaleController@SaveExcuse');
    Route::get('missed', 'SaleController@Missed');
    Route::get('commission', 'SaleController@Commission');
    Route::get('pdf-html/{id?}', 'SaleController@PdfHTML');
    Route::get('download-pdf/{id?}', 'SaleController@DownloadPdf');
    Route::get('send-mail/{id?}', 'SaleController@sendEmail');
    Route::get('/save-full-quote', 'SaleController@SaveFullQuote');
    Route::get('/order-management', 'SaleController@OrderManagement');
    Route::get('modal-cancel-order/{id?}/{cancel?}', 'SaleController@ModalCancelOrder');
    Route::post('save-cancel-order/{id?}', 'SaleController@SaveCancelOrder');
    Route::any('save-approve-order/{id?}', 'SaleController@SaveApproveOrder');
    Route::get('modal-approve-order/{id?}/{cancel?}', 'SaleController@ModalApproveOrder');
    Route::get('datasheet', 'SaleController@Datasheet');
    Route::get('create-datasheet', 'SaleController@CreateDatasheet');
    Route::get('view-datasheet', 'SaleController@ViewDatasheet');
    Route::post('save-datasheet', 'SaleController@SaveDatasheet');
    Route::post('datasheet-pdf', 'SaleController@DatasheetPdf');
    Route::get('datasheet-pdf-html/{id?}', 'SaleController@DatasheetPdfHtml');
    Route::get('download-datasheet/{id?}','SaleController@DownloadDatasheet');
  });
  // end sales module
  // ******************************************************************************************
  // for accounting module
  //*******************************************************************************************
  Route::group(['prefix' => 'accounting'], function () {
    Route::get('/', 'AccountingController@index');
    Route::get('purchase-orders', 'AccountingController@PurchaseOrders');
    Route::get('pending-purchase-orders', 'AccountingController@PendingPurchaseOrders');
    Route::get('approved-purchase-orders', 'AccountingController@ApprovedPurchaseOrders');
    Route::get('rejected-purchase-orders', 'AccountingController@RejectedPurchaseOrders');
    Route::post('reject-purchase-order', 'AccountingController@RejectPurchaseOrder');
    Route::post('approve-purchase-order', 'AccountingController@ApprovePurchaseOrder');
    Route::get('purchase-order-details/{id?}', 'AccountingController@PurchaseOrderDetails');
    Route::get('accounts', 'AccountingController@Accounts');
    Route::get('add-supplier', 'AccountingController@AddSupplier');
    Route::get('add-supplier-contact', 'AccountingController@AddSupplierContact');
    Route::post('save-supplier', 'AccountingController@SaveSupplier');
    Route::post('save-supplier-contact', 'AccountingController@SaveSupplierContact');
    Route::get('supplier-profile/{id?}', 'AccountingController@SupplierProfile');
    Route::post('update-supplier', 'AccountingController@UpdateSupplier');
    Route::get('orders-by-date', 'AccountingController@OrdersByDate');
    Route::get('payment-schedule', 'AccountingController@PaymentSchedule');
    Route::get('calendar-transactions', 'AccountingController@CalendarTransactions');
    Route::post('get-lead-contact', 'AccountingController@GetLeadContact');
    Route::post('save-payment-record', 'AccountingController@SavePaymentRecord');
    Route::get('invoices', 'AccountingController@Invoices');
    Route::get('create-invoice', 'AccountingController@CreateInvoice');
    Route::post('account-detail', 'AccountingController@AccountDetail');
    Route::post('save-invoice', 'AccountingController@SaveInvoice');
    Route::get('invoices-by-date', 'AccountingController@InvoicesByDate');
    Route::get('overview-and-reports', 'AccountingController@OverviewAndReports');
    Route::get('transactions','AccountingController@Transactions');
    Route::post('save-transaction','AccountingController@SaveTransaction');
    Route::get('transactions-by-date','AccountingController@TransactionsByDate');
    Route::get('accounts-payable','AccountingController@AccountsPayable');
    Route::get('accounts-receivable','AccountingController@AccountsReceivable');
    Route::post('accounts-by-category','AccountingController@AccountsByCategory');
    Route::post('save-account-category','AccountingController@SaveAccountCategory');
    Route::post('save-account','AccountingController@SaveAccount');
    Route::get('delete-account/{id?}', 'AccountingController@DeleteAccount');
    Route::post('get-account-detail', 'AccountingController@GetAccountDetail');
    Route::post('save-edit-account','AccountingController@SaveEditAccount');


  });
  //*******************************************************************************************
  // for orders module
  Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'OrdersController@index');
    Route::get('orders-table', 'OrdersController@OrdersTable');
    Route::get('order-detail/{var?}','OrdersController@OrderDetail');
    Route::get('order-details-table', 'OrdersController@OrderDetailsTable');
    Route::get('pin-to-top/{var?}','OrdersController@PinToTop');
    Route::get('client-list','OrdersController@ClientList');
    Route::get('order-management/{var?}','OrdersController@OrderManagement');
    Route::post('save-order-data', 'OrdersController@SaveOrderData');
    Route::post('save-order-details-data', 'OrdersController@SaveOrderDetailsData');
    Route::post('save-order-editable', 'OrdersController@SaveOrderEditable');
    Route::get('delete-order/{var?}','OrdersController@DeleteOrder');


  });

  //*******************************************************************************************
  // ajax controller
      Route::post('/check-lead-contact', 'AjaxController@CheckLeadContact');
      Route::get('/change-sign-pla', 'AjaxController@ChnageSignPla');
      Route::get('/save-pla-value', 'AjaxController@SavePlaValue');
      Route::post('/get-products', 'AjaxController@GetProducts');
      Route::get('/attr-val-checkbox', 'AjaxController@AttrValCheckbox');
      Route::get('/sel-all-attr-val', 'AjaxController@SelAllAttrVal');
      Route::get('/sel-none-attr-val', 'AjaxController@SelNoneAttrVal');
      Route::get('/attr-val-uncheck', 'AjaxController@AttrValUncheck');
      Route::get('/generate-products', 'AjaxController@GenerateProducts');
      Route::post('/save-product-id', 'AjaxController@SaveProductId');
      Route::post('/save-notes-assign', 'AjaxController@SaveNotesAssign');
      Route::get('/delete-assign-val', 'AjaxController@DeleteAssignVal');
      Route::get('/delete-assign-val-view-all', 'AjaxController@DeleteAssignValViewAll');
      Route::get('/change-tolerance', 'AjaxController@ChangeTolerance');
      Route::get('/approve-price', 'AjaxController@ApprovePrice');
      Route::get('/approve-class-tole', 'AjaxController@ApproveClassTole');
      Route::get('/search-products', 'AjaxController@SearchProducts');
      Route::get('/reverse-action', 'AjaxController@ReverseAction');
      Route::get('/select-client', 'AjaxController@SelectClient');
      Route::get('/select-contact', 'AjaxController@SelectContact');
      Route::get('/get-approved-attr', 'AjaxController@GetApprovedAttr');
      Route::post('/check-product-exist', 'AjaxController@CheckProductExist');
      Route::get('/save-ajax-quote', 'AjaxController@SaveAjaxQuote');
      Route::get('/get-missed-graph', 'AjaxController@GetMissedGraph');
      Route::get('/search-sales-report', 'AjaxController@SearchSalesReport');
      Route::get('/search-commission', 'AjaxController@SearchCommission');
      Route::get('/save-flag-status', 'AjaxController@SaveFlagStatus');
      Route::get('/save-confirm-status', 'AjaxController@SaveConfirmStatus');
      Route::get('/save-postpone-status', 'AjaxController@SavePostponeStatus');
      Route::get('/search-order', 'AjaxController@SearchOrder');
      Route::get('/save-sort-order', 'AjaxController@SaveSortOrder');
      Route::post('/select-product-class', 'AjaxController@SelectProductClass');
      Route::any('/edit-price', 'AjaxController@EditPrice');
      Route::any('/get-attributes', 'AjaxController@GetAttributes');
      Route::any('/get-order-info', 'AjaxController@GetOrderInfo');
      Route::any('/send-notify', 'AjaxController@SendNotify');
      Route::any('/eng-search-order', 'AjaxController@EngSearchOrder');
      Route::post('/get-sequence-no-details', 'AjaxController@GetSequenceNoDetails');
      Route::get('/get-transit-inventory', 'AjaxController@GetTransitInventory');
      Route::get('/get-delivered-inventory', 'AjaxController@GetDeliveredInventory');
      Route::get('/get-inventory', 'AjaxController@GetInventory');
      Route::get('/get-raw-material-table', 'AjaxController@GetRawMaterialTable');
      Route::get('/get-checkout-pending-table', 'AjaxController@GetCheckoutPendingTable');
      Route::get('/get-order-fulfillment-table','AjaxController@GetOrderFulfillmentTable');
      Route::get('/get-checkout-approved-table', 'AjaxController@GetCheckoutApprovedTable');
      Route::get('/get-checkout-rejected-table', 'AjaxController@GetCheckoutRejectedTable');
      Route::get('/get-datasheet', 'AjaxController@GetDatasheet');
      Route::get('/search-datasheet', 'AjaxController@SearchDatasheet');
      Route::post('supplier-information', 'AjaxController@SupplierInformation');

  // end ajax controller
  // ******************************************************************************************
});

// client routes
Route::group(['middleware' => 'App\Http\Middleware\ClientMiddleware'], function(){
  Route::group(['prefix' => 'client'], function () {
    Route::get('home','ClientController@home');
    Route::get( 'users', 'ClientController@Users' );
    Route::get('users-table', 'ClientController@UsersTable');
    Route::get('add-user/{var?}', 'ClientController@AddUser');
    Route::post('save-client', 'ClientController@SaveClient' );
    Route::post('save-client-settings/{id?}', 'ClientController@SaveClientSettings');
    Route::get('edit-user/{var?}', 'ClientController@EditUser');
    Route::get('disable-user/{var?}', 'ClientController@DisableUser');
    Route::get('delete-user/{var?}', 'ClientController@DeleteUser');
    Route::get('orders','ClientController@Orders');
    Route::get('new-order','ClientController@NewOrder');
    Route::post('save-new-order','ClientController@SaveClientOrder');
    Route::get( 'order-progress', 'ClientController@OrderProgress' );
    Route::get('client-orders-table', 'ClientController@ClientOrdersTable');
    Route::get('pin-to-top/{var?}', 'ClientController@PinToTop');
    Route::get('delete-order/{var?}','ClientController@DeleteOrder');
    Route::get('order-details-table', 'ClientController@OrderDetailsTable');
    Route::get('order-history', 'ClientController@OrderHistory');
    Route::get('order-history-table', 'ClientController@OrdersHistoryTable');
    Route::get('confirm-order/{id?}', 'ClientController@ConfirmOrder');
    Route::post('place-order/{id?}/{value?}', 'ClientController@PlaceOrder');
    Route::post('preview-order', 'ClientController@PreviewOrder');
    Route::get('order-history-detail/{var?}','ClientController@OrderHistoryDetail');
    Route::get('order-progress-detail/{var?}','ClientController@OrderProgressDetail');
    Route::post('save-order-editable', 'ClientController@SaveOrderEditable');
    Route::get('edit-profile/{var?}','ClientController@EditProfile');
    Route::get('edit-details/{var?}','ClientController@EditDetails');
    Route::post('save-order-details-data', 'ClientController@SaveOrderDetailsData');

  });
  Route::get('client-settings', 'ClientController@ClientSettings');
});
