/**
 * Copyright © 2007-2014 The Galette Team
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Javascript
 * @package   Galette
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2007-2014 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7dev - 2007-10-06
 */

$(function() {
    /* Fomantic UI components */
    $('.ui.sidebar').sidebar('attach events', '.toc.item');
    $('.ui.dropdown').dropdown();
    $('.ui.accordion').accordion();
    $('#contrib-rangestart').calendar({
      monthFirst: false,
      type: 'date',
      formatter: {
        date: function (date, settings) {
          if (!date) return '';
          var day = date.getDate();
          var month = date.getMonth() + 1;
          var year = date.getFullYear();
          return day + '/' + month + '/' + year;
        }
      },
      endCalendar: $('#contrib-rangeend')
    });
    $('#contrib-rangeend').calendar({
      monthFirst: false,
      type: 'date',
      formatter: {
        date: function (date, settings) {
          if (!date) return '';
          var day = date.getDate();
          var month = date.getMonth() + 1;
          var year = date.getFullYear();
          return day + '/' + month + '/' + year;
        }
      },
      startCalendar: $('#contrib-rangestart')
    });

    /* Tooltips position on right aligned dropdowns */
    $('.ui.dropdown.right-aligned').tooltip({
        items: 'a.item',
        position: { 
          my: 'left-100% center', 
          at: 'left center' 
        },
    });
});