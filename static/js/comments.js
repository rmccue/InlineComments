(function ($) {
	var InlineComments = {};

	InlineComments.nearestCommentable = function (elem) {
		return $(elem).closest('.inlinecomments-enabled');
	};

	InlineComments.nearestKey = function (elem) {
		return InlineComments.nearestCommentable( elem ).data('paragraphkey');
	};

	InlineComments.UI = { buttons: {} };
	InlineComments.UI.buttons.items = {};
	InlineComments.UI.buttons.setup = function () {
		$buttonHolder = $('<ul id="inlinecomments-buttons"></ul>');

		$('.inlinecomments-enabled').each(function () {
			InlineComments.UI.buttons.add(this);
		});

		$('body').append($buttonHolder);
		$('#inlinecomments-buttons')
			.on('mouseover', '.inlinecomments-button', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.inlinecomments-button', function () {
				$(this).removeClass('active');
			})
			.on('click', '.inlinecomments-button', InlineComments.UI.buttons.click);
	};

	InlineComments.UI.buttons.add = function (elem) {
		var $button = $('<span class="inlinecomments-button"><i class="ic-genericon ic-genericon-comment" />5</span>');
		var key = InlineComments.nearestKey(elem);
		var $para = $(elem);

		// Set the position based on the parent
		var parentOffset = $para.offset();
		var childOffset = parentOffset;
		childOffset.left += $para.outerWidth();
		$button.offset(childOffset);

		// Set the paragraph key so we can find it later
		$button.data('paragraphkey', key);
		InlineComments.UI.buttons.items[key] = $button;

		$buttonHolder.append($button);
	};

	InlineComments.UI.buttons.get = function (key) {
		return InlineComments.UI.buttons.items[ key ];
	};
	InlineComments.UI.buttons.attached = function (elem) {
		var key = InlineComments.nearestKey(elem);
		return InlineComments.UI.buttons.get( key );
	};
	InlineComments.UI.buttons.click = function () {
		var key = $(this).data('paragraphkey');
		// open the model here, or whatever
	};

	$(document).ready(function () {
		InlineComments.UI.buttons.setup();
		$('body')
			.on('mouseover', '.inlinecomments-enabled', function () {
				var button = InlineComments.UI.buttons.attached(this);
				// console.log(button);
				button.addClass('active');
			})
			.on('mouseout', '.inlinecomments-enabled', function () {
				var button = InlineComments.UI.buttons.attached(this);
				button.removeClass('active');
			})
	});
})(jQuery);