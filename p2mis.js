// hello

(function($) {

function notify_posts(e, newPosts) {
	if( ! window.webkitNotifications || window.webkitNotifications.checkPermission() != 0 ) {
		return;
	}

	var theList = $(newPosts.html);

	$.each( theList, function(i, li) {
		if( li.tagName != 'LI' ) {
			return;
		}

		li = $(li);

		var title = 'Post by ' + li.find('h4>a').text(),
			content = li.find('.postcontent').text().trim().split("\n")[0];

		var n = window.webkitNotifications.createNotification( 'https://www.plymouth.edu/webapp/devwiki/images/f/f3/Exclamation_mark.png', title, content );
		n.onclick = function(n) {
			window.focus();
			this.cancel();
		};
		n.show();
	});
}
$(document).bind( 'p2.newposts', notify_posts );

function notify_comments(e, newComments) {
	$.each(newComments.comments, function(i, comment) {
		if( comment.html == '' ) {
			return;
		}

		if( i > 1 ) return;

		var $comment = $(comment.html),
			title = 'Comment by ' + $comment.find('h4')[0].firstChild.textContent.trim(),
			content = $comment.find('.commentcontent').text().trim().split("\n")[0];

		var n = window.webkitNotifications.createNotification( 'https://www.plymouth.edu/webapp/devwiki/images/f/f3/Exclamation_mark.png', title, content );
		n.onclick = function(n) {
			window.focus();
			this.cancel();
		};
		n.show();
	});
}
$(document).bind( 'p2.newcomments', notify_comments );

var a = $('<a href="#">Enable desktop notifications</a>.').bind('click', function(e){
	e.stopPropagation();
	e.preventDefault();

	window.webkitNotifications.requestPermission();
	console.log( window.webkitNotifications.createNotification(null, 'foo', 'bar') );
});
$('#footer p').append(a);

$('ul#postlist .tag-protip').prepend('<h2 class="protip"><span class="accent">&#10144;</span> Ultra Awesome <span class="what"></span> Pro Tip:</h2>');
$('ul#postlist .tag-protip.tag-vim')
	.find('.what')
	.html('PHP')
	.end()
	.attr('title', 'PHP Pro Tip');

$('ul#postlist .tag-protip.tag-vim, ul#postlist .tag-protip.tag-unix, ul#postlist .tag-protip.tag-linux')
	.find('.what')
	.html('Command Line')
	.end()
	.find('.accent')
	.attr('title', 'Command Line Pro Tip')
	.html('&#9000;'); // keyboard

$('ul#postlist .tag-protip.tag-database, ul#postlist .tag-protip.tag-oracle, ul#postlist .tag-protip.tag-mysql')
	.find('.what')
	.html('Database')
	.end()
	.find('.accent')
	.attr('title', 'Database Pro Tip')
	.html('&#9638;'); // hash box

$('ul#postlist .tag-protip.tag-vim')
	.find('.what')
	.html('VIM')
	.end()
	.find('.accent')
	.attr('title', 'VIM Pro Tip')
	.html('&#9998;'); // pencil

$('ul#postlist .tag-protip.tag-git')
	.find('.what')
	.html('Git')
	.end()
	.find('.accent')
	.attr('title', 'Git Pro Tip')
	.html('&#10026;'); // circled star

$('ul#postlist .tag-protip.tag-warning')
	.find('.what')
	.append(' Warning')
	.end()
	.find('.accent')
	.attr('title', 'Pro Warning')
	.html('&#9760;'); // skull and crossbones

})(jQuery);
