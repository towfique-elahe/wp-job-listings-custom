jQuery(document).ready(function ($) {
    function fetchJobs(page = 1) {
        let filters = {
            action: 'filter_jobs',
            nonce: job_ajax_obj.nonce,
            search: $('#search').val(),
            paged: page,
            posts_per_page: $('#jobs_per_page').val() || job_ajax_obj.default_per_page,
            order: $('#sort_order').val()
        };

        // Gather all filters
        $('.filter-select').each(function () {
            const name = $(this).attr('name');
            const value = $(this).val();
            if (value) {
                filters[name] = [value];
            }
        });

        $.ajax({
            url: job_ajax_obj.ajax_url,
            type: 'POST',
            data: filters,
            beforeSend: function () {
                $('#job-results-grid, #job-results-list').html('<p class="jobs-msg">Loading jobs...</p>');
            },
            success: function (res) {
                $('#job-results-grid').html(res.grid);
                $('#job-results-list').html(res.list);
            }
        });
    }

    // Filter events
    $('#search, #jobs_per_page, #sort_order').on('change keyup', function () {
        fetchJobs();
    });

    $('.filter-select').on('change', function () {
        fetchJobs();
    });

    $('#job-results-grid, #job-results-list').on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).data('page');
        fetchJobs(page);
    });

    $('.reset-button').on('click', function (e) {
        e.preventDefault();
        $('.filter-select').val('');
        $('#search').val('');
        fetchJobs();
    });

    // View toggle
    $('.view-icon').on('click', function () {
        let view = $(this).data('view');

        $('.view-icon').removeClass('active');
        $(this).addClass('active');

        if (view === 'list') {
            $('#job-results-grid').hide();
            $('#job-results-list').show();
        } else {
            $('#job-results-list').hide();
            $('#job-results-grid').show();
        }
    });

    const defaultView = job_ajax_obj.default_view || 'grid';
    if (defaultView === 'list') {
        $('#job-results-grid').hide();
        $('#job-results-list').show();
        $('.view-icon.grid').removeClass('active');
        $('.view-icon.list').addClass('active');
    } else {
        $('#job-results-list').hide(); // Only hide list if default is grid
    }

    // Mobile sidebar toggle
    $('.mobile-filter-toggle').on('click', function () {
        $('.custom-job-listing .sidebar').toggleClass('active');
    });

    $('.close-sidebar').on('click', function () {
        $('.custom-job-listing .sidebar').removeClass('active');
    });

    $(document).on('click', function (e) {
        if (
            $('.custom-job-listing .sidebar').hasClass('active') &&
            !$(e.target).closest('.sidebar, .mobile-filter-toggle').length
        ) {
            $('.custom-job-listing .sidebar').removeClass('active');
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            $('.custom-job-listing .sidebar').removeClass('active');
        }
    });

    // Initial load
    fetchJobs();
});