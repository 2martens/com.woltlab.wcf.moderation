/**
 * Namespace for moderation related classes.
 */
WCF.Moderation = { };

/**
 * Handles content report.
 * 
 * @param	string		objectType
 * @param	string		buttonSelector
 */
WCF.Moderation.Report = Class.extend({
	/**
	 * list of buttons
	 * @var	object
	 */
	_buttons: { },
	
	/**
	 * button selector
	 * @var	string
	 */
	_buttonSelector: '',
	
	/**
	 * dialog overlay
	 * @var	jQuery
	 */
	_dialog: null,
	
	/**
	 * notification object
	 * @var	WCF.System.Notification
	 */
	_notification: null,
	
	/**
	 * object id
	 * @var	integer
	 */
	_objectID: 0,
	
	/**
	 * object type name
	 * @var	string
	 */
	_objectType: '',
	
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,
	
	/**
	 * Creates a new WCF.Moderation.Report object.
	 * 
	 * @param	string		objectType
	 * @param	string		buttonSelector
	 */
	init: function(objectType, buttonSelector) {
		this._objectType = objectType;
		this._buttonSelector = buttonSelector;
		
		this._buttons = { };
		this._notification = null;
		this._objectID = 0;
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		
		this._initButtons();
		
		WCF.DOMNodeInsertedHandler.addCallback('WCF.Moderation.Report' + this._objectType.hashCode(), $.proxy(this._initButtons, this));
	},
	
	/**
	 * Initializes the report feature for all matching buttons.
	 */
	_initButtons: function() {
		var self = this;
		$(this._buttonSelector).each(function(index, button) {
			var $button = $(button);
			var $buttonID = $button.wcfIdentify();
			
			if (!this._buttons[$buttonID]) {
				this._buttons[$buttonID] = $button;
				$button.click($.proxy(self._click, self)));
			}
		});
	},
	
	/**
	 * Handles clicks on a report button.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		this._objectID = $(event.currentTarget).data('objectID');
		
		this._proxy.setOption('data', {
			actionName: 'prepareReport',
			className: 'wcf\\data\\moderation\\queue\\ModerationQueueAction',
			parameters: {
				objectID: this._objectID,
				objectType: this._objectType
			}
		});
		this._proxy.sendRequest();
	},
	
	/**
	 * Handles successful AJAX requests.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
	_success: function(data, textStatus, jqXHR) {
		// object has been successfully reported
		if (data.returnValues.reported) {
			if (this._notification === null) {
				this._notification = new WCF.System.Notification(WCF.Language.get('wcf.moderation.report.success'));
			}
			
			// show success and close dialog
			this._dialog.wcfDialog('close');
			this._notification.show();
		}
		else if (data.returnValues.template) {
			// display template
			this._showDialog(data.returnValues.template);
			
			if (!data.returnValues.alreadyReported) {
				// bind event listener for buttons
				this._dialog.find('.jsSubmitReport').click($.proxy(this._submit, this));
			}
		}
	},
	
	/**
	 * Displays the dialog overlay.
	 * 
	 * @param	string		template
	 */
	_showDialog: function(template) {
		if (this._dialog === null) {
			this._dialog = $('#moderationReport');
			if (!this._dialog.length) {
				this._dialog = $('<div id="moderationReport" />').hide().appendTo(document.body);
			}
		}
		
		this._dialog.html(template).wcfDialog({
			title: WCF.Language.get('wcf.moderation.report.reportContent')
		});
	},
	
	/**
	 * Submits a report unless the textarea is empty.
	 */
	_submit: function() {
		var $text = this._dialog.find('.jsReportMessage').val();
		if ($text != '') {
			this._proxy.setOption('data', {
				actionName: 'report',
				className: 'wcf\\data\\moderation\\queue\\ModerationQueueAction',
				parameters: {
					objectID: this._objectID,
					objectType: this._objectType
				}
			});
			this._proxy.sendRequest();
		}
	}
});