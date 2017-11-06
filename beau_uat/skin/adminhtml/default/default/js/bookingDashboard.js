var ADMINAPP = ADMINAPP || {};

(function($){
  'use strict';

  var bookingRequest = null,
    root;

  ADMINAPP.bookingDashboard = {
    cache: {
      $dashboard: $('.booking-dashboard'),
      $bookingStates: $('.booking-states'),
      $bookingLists: $('.booking-list'),
      $datePicker: $('.datepicker'),
      $filters: $('.booking-filter :radio'),
      $selectDateInput: $('.booking-filter :radio[data-date-type="select"]')
    },
    isIdle: true,
    const: {},
    init: function() {
      root = this;

      var onUpdate = function() { root.loadBookings(); }
      var onSelectDate = function() {
        root.cache.$selectDateInput.prop("checked", true);
        root.loadBookings();
      }

      // set consts
      root.const.DATESTARTFORMAT = 'h:mm a (Do MMM)';
      root.const.DATEFILTERFORMAT = 'YYYY-MM-DD';
      root.const.DATEFINISHFORMAT = 'h:mm a';
      root.const.DATEPICKERFORMAT = 'DD/MM/YYYY';
      root.const.GETURL = root.cache.$dashboard.data('get-url');
      root.const.ORDERBY = 'startTime';
      root.const.TIMER = 60 * 1000;
      root.const.UPDATEURL = root.cache.$dashboard.data('update-url');

      this.handleLinks();
      this.addListDrags();
      this.loadBookings();

      root.cache.$datePicker
        .val( moment().format(root.const.DATEPICKERFORMAT) )
        .datepicker({
          dateFormat: 'dd/mm/yy',
          onSelect: onSelectDate
        });

      root.cache.$filters.on('change', onUpdate);

      setInterval(onUpdate, root.const.TIMER);
    },

    addListDrags: function() {
      var onDrop = function(event) {
        event.preventDefault();
        var data = event.originalEvent.dataTransfer.getData("text");
        if ( $(event.target).hasClass('booking-list') ) {
          var droppedElem = document.getElementById(data);
          event.target.appendChild(droppedElem);
          root.updateBookings( event.target, droppedElem );
        }
        $(event.target).removeClass('drag-over');
      }

      var onDragOver = function(event) {
        event.preventDefault();
        $(event.target).addClass('drag-over');
      }

      var onDragLeave = function(event) {
        event.preventDefault();
        $(event.target).removeClass('drag-over');
      }

      for (var i = 0, count = root.cache.$bookingLists.length; i < count; i++) {
        var $thisList = root.cache.$bookingLists.eq(i);

        $thisList
          .addClass('ready')
          .on('drop', onDrop)
          .on('dragover', onDragOver)
          .on('dragleave', onDragLeave);
      }
    },

    addBookingDrags: function() {
      var $bookingItems = $('.booking-states li');

      var onDragStart = function(event) {
        event.originalEvent.dataTransfer.setData('text', event.target.id);
        $(event.target).parent().addClass('drag-parent');
        root.cache.$bookingStates.addClass('dragging');
        root.isIdle = false;
      }

      var onDragEnd = function(event) {
        $('.booking-list.drag-parent').removeClass('drag-parent');
        root.cache.$bookingStates.removeClass('dragging');
        root.isIdle = true;
      }


      for (var i = 0, count = $bookingItems.length; i < count; i++) {
        var $thisItem = $bookingItems.eq(i);

        $thisItem
          .attr('draggable',true)
          .attr('id','item-'+i)
          .on('dragstart', onDragStart)
          .on('dragend', onDragEnd);
      }
    },

    handleLinks: function() {
      // iFrames suck
      /*
      var $links = $('.booking-links button'),
        $iframe = $('.booking-iframe iframe');

      var showLink = function() {
        $iframe.attr('src', $(this).data('url'));
        root.cache.$dashboard.addClass('show-iframe');
      }

      var showBookings = function() {
        root.cache.$dashboard.removeClass('show-iframe');
      }

      for ( var i=0,n=$links.length; i<n; i++) {
        var $thisButton = $links.eq(i);

        if ( $thisButton.data('url') ) {
          $thisButton.on('click',showLink);
        } else {
          $thisButton.on('click',showBookings);
        }
      }
      */
    },

    loadBookings: function() {
      var data = {},
        $activeDateFilter = null,
        dateFilterType = null;

      var handleSeccuss = function(data) {
        var orderedData = null;

        root.resetLists();

        if ( data.error == 0 && data.data.length ) {
          orderedData = _.sortBy(data.data, [root.const.ORDERBY]);
          for ( var i=0,n=orderedData.length; i<n; i++) {
            root.addBooking(orderedData[i]);
          }
          root.addBookingDrags();
        }
        root.isIdle = true;
      }

      var handleError = function(jqXHR, status, errorThrown) {
        if ( status != 'abort' ) {
          console.error('Get bookings failed', status, errorThrown);
        }
        root.isIdle = true;
      }

      // load bookings (only if UI is idle)
      if ( root.isIdle ) {
        $activeDateFilter = $('.booking-filter :radio:checked');

        switch($activeDateFilter.data('date-type')) {
          case('select'):
            var selectedData = root.cache.$datePicker.val();
            data.startDate = moment(selectedData,root.const.DATEPICKERFORMAT).format(root.const.DATEFILTERFORMAT);
            if ( data.startDate === 'Invalid date' ) {
              data.startDate = moment().format(root.const.DATEFILTERFORMAT);
              data.endDate = moment().format(root.const.DATEFILTERFORMAT);
            } else {
              data.endDate = moment(selectedData,root.const.DATEPICKERFORMAT).format(root.const.DATEFILTERFORMAT);
            }
            break;

          case('thisweek'):
            data.startDate = moment().startOf('isoWeek').format(root.const.DATEFILTERFORMAT);
            data.endDate = moment().endOf('isoWeek').format(root.const.DATEFILTERFORMAT);
            break;

          case('lastweek'):
            data.startDate = moment().subtract(1, 'weeks').startOf('isoWeek').format(root.const.DATEFILTERFORMAT);
            data.endDate = moment().subtract(1, 'weeks').endOf('isoWeek').format(root.const.DATEFILTERFORMAT);
            break;

          case('tomorrow'):
            data.startDate = moment().add(1, 'days').format(root.const.DATEFILTERFORMAT);
            data.endDate = moment().add(1, 'days').format(root.const.DATEFILTERFORMAT);
            break;

          case('today'):
          default:
            data.startDate = moment().format(root.const.DATEFILTERFORMAT);
            data.endDate = moment().format(root.const.DATEFILTERFORMAT);
        }

        root.isIdle = false;
        bookingRequest = $.ajax({
          url: root.const.GETURL,
          data: data,
          method: 'GET',
          dataType: 'JSON',
          success: handleSeccuss,
          error: handleError
        });
      }
    },

    updateBookings: function(listElem, bookingElem) {
      var $list = $(listElem),
        $booking = $(bookingElem),
        newState = $list.data('state'),
        bookingId = $booking.data('booking-id'),
        request;

      var handleSeccuss = function() {
        root.isIdle = true;
      }

      var handleError = function(jqXHR, status, errorThrown) {
        console.error('Update failed', status, errorThrown);
        root.isIdle = true;
      }

      root.isIdle = false;

      request = $.ajax({
        url: root.const.UPDATEURL,
        data: { id: bookingId, state: newState  },
        method: 'GET',
        success: handleSeccuss,
        error: handleError
      });
    },

    addBooking: function(data) {
      var $list = root.cache.$bookingStates.find('ul[data-state="'+data.state+'"]'),
        formatStart = moment(data.startTime).format(root.const.DATESTARTFORMAT),
        formatEnd = moment(data.finishTime).format(root.const.DATEFINISHFORMAT);

      var $booking = $('<li />')
        .attr('data-booking-id',data.id)
        .append( $('<div />').addClass('name').html(data.name) )
        .append( $('<div />').html(data.serviceType) )
        .append( $('<div />').html('Bay '+data.bayNumber) )
        .append( $('<div />').html('Start: '+formatStart) )
        .append( $('<div />').html('Finish '+formatEnd) )
        .appendTo($list);
    },

    resetLists: function() {
      root.cache.$bookingLists.empty();
    }

  };

  $(function(){
    ADMINAPP.bookingDashboard.init();
  });

})(jQuery);