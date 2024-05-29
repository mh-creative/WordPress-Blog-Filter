(function($) {
    $(document).ready(function() {
        function showLoadingTextBesideButton() {
            $('#reset-filters').after('<span class="loading-text ml-2"> Loading...</span>');
        }

        function hideLoadingTextBesideButton() {
            $('.loading-text').remove();
        }

        function showPagination() {
            $('#pagination').show();
        }

        function hidePagination() {
            $('#pagination').hide();
        }

        function filterPosts(page = 1) {
            var categoryID = $('#category-filter').val();
            var tagID = $('#tag-filter').val();
            var ajaxurl = my_ajax_object.ajax_url;

            showLoadingTextBesideButton();
            $('#blog-posts-container').html('<p>Loading posts...</p>');

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'filter_posts',
                    category: categoryID,
                    tag: tagID,
                    page: page
                },
                success: function(response) {
                    var data = $.parseJSON(response);

                    hideLoadingTextBesideButton();
                    $('#blog-posts-container').html(data.posts);

                    if (data.total_posts <= 5) {
                        hidePagination();
                    } else {
                        showPagination();
                        $('#pagination').html(data.pagination);
                    }

                    $('html, body').animate({
                        scrollTop: $('#category-filter').offset().top - 300
                    }, 500);
                }
            });
        }

        $('#category-filter, #tag-filter').change(function() {
            filterPosts();
        });

        $(document).on('click', '#pagination button', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            filterPosts(page);
        });

        filterPosts();

        $('#reset-filters').click(function() {
            $('#category-filter').val('');
            $('#tag-filter').val('');
            filterPosts();
        });
    });
})(jQuery);
