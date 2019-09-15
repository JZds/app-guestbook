import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../css/app.css';
import $ from 'jquery';
import routes from '../../public/js/fos_js_routes.json';
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
window.Routing = Routing;

$('#commentInput').keyup(function () {
    if ($('#commentInput').val().trim().length === 0) {
        $('#submitComment').hide();
        $('#cancelComment').hide();
    } else {
        $('#submitComment').show();
        $('#cancelComment').show();
    }
});

$('#cancelComment').click(function(){
    $('#commentInput').val('');
    $('#submitComment').hide();
    $('#cancelComment').hide();
});

$('.approve-comment').click(function(){
    sendCommentPutRequest('app_guest_book.approve_comment', this.id);
});

$('.disapprove-comment').click(function(){
    sendCommentPutRequest('app_guest_book.disapprove_comment', this.id);
});

$('.delete-comment').click(function(){
    sendCommentPutRequest('app_guest_book.delete_comment', this.id);
});

export default function sendCommentPutRequest(route, commentId) {
    $.ajax({
        type: "PUT",
        url: Routing.generate(route, {commentId: commentId}),
        success: (function() {
            location.reload();
        })
    });
}
