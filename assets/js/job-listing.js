jQuery(document).ready(function ($) {
    function fetchJobs(page = 1) {
        let filters = {
            action: 'filter_jobs',
            nonce: job_ajax_obj.nonce,
            search: $('#search').val(),
            paged: page,
            posts_per_page: $('#jobs_per_page').val(),
            order: $('#sort_order').val()
        };

        // Gather all checked filters
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
                $('#job-results').html('<p class="jobs-msg">Loading jobs...</p>');
            },
            success: function (res) {
                if (res.trim() === '') {
                    $('#job-results').html('<p class="jobs-msg no-results">No jobs available.</p>');
                } else {
                    $('#job-results').html(res);
                }
            }
        });
    }

    // Trigger events
    $('#search, #jobs_per_page, #sort_order').on('change keyup', function () {
        fetchJobs();
    });

    $('.filter-select').on('change', function () {
        fetchJobs();
    });

    $('#job-results').on('click', '.pagination a', function (e) {
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

    // Initial load
    fetchJobs();

  $('.mobile-filter-toggle').on('click', function () {
    $('.custom-job-listing .sidebar').toggleClass('active');
  });

  $('.close-sidebar').on('click', function () {
    $('.custom-job-listing .sidebar').removeClass('active');
  });

  // Optional: Close sidebar on outside click or Esc
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

  $('.view-icon').on('click', function () {
    var view = $(this).data('view');

    // Update icon active state
    $('.view-icon').removeClass('active');
    $(this).addClass('active');

    // Toggle classes on job list
    if (view === 'list') {
        $('#job-results').addClass('list-view');
    } else {
        $('#job-results').removeClass('list-view');
    }
});

});