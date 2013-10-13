(function ($) {
	var InlineComments = {};

	InlineComments.nearestCommentable = function (elem) {
		return $(elem).closest('.inlinecomments-enabled');
	};

	InlineComments.nearestKey = function (elem) {
		return InlineComments.nearestCommentable( elem ).data('paragraphkey');
	};

	InlineComments.UI = {};
	InlineComments.UI.buttons = {};
	InlineComments.UI.addButtons = function () {
		$buttonHolder = $('<ul id="inlinecomments-buttons"></ul>');

		$('.inlinecomments-enabled').each(function () {
			var $button = $('<span class="inlinecomments-button"><i class="ic-genericon ic-genericon-comment" />5</span>');
			var key = InlineComments.nearestKey(this);
			var $para = $(this);

			// Set the position based on the parent
			var parentOffset = $para.offset();
			var childOffset = parentOffset;
			childOffset.left += $para.outerWidth();
			$button.offset(childOffset);

			// Set the paragraph key so we can find it later
			$button.data('paragraphkey', key);
			InlineComments.UI.buttons[key] = $button;

			$buttonHolder.append($button);
		});

		$('body').append($buttonHolder);
	};

	InlineComments.UI.getButton = function (key) {
		return InlineComments.UI.buttons[ key ];
	};
	InlineComments.UI.attachedButton = function (elem) {
		var key = InlineComments.nearestKey(elem);
		return InlineComments.UI.getButton( key );
	}

	$(document).ready(function () {
		InlineComments.UI.addButtons();
		$('body')
			.on('mouseover', '.inlinecomments-enabled', function () {
				var button = InlineComments.UI.attachedButton(this);
				// console.log(button);
				button.addClass('active');
			})
			.on('mouseout', '.inlinecomments-enabled', function () {
				var button = InlineComments.UI.attachedButton(this);
				button.removeClass('active');
			})
		$('#inlinecomments-buttons')
			.on('mouseover', '.inlinecomments-button', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.inlinecomments-button', function () {
				$(this).removeClass('active');
			})
			.on('click', '.inlinecomments-button', function () {
				var $para = $(this).closest('.inlinecomments-enabled');
				var pkey = $para.data('paragraphkey');
				console.log(pkey);
			});
	});
})(jQuery);