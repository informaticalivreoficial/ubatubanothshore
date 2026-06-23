import './bootstrap';

import Swal from 'sweetalert2'
window.Swal = Swal

import flatpickr from "flatpickr"
import { Portuguese } from "flatpickr/dist/l10n/pt.js"

window.flatpickr = flatpickr
window.FlatpickrPortuguese = Portuguese

import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import ptBrLocale from '@fullcalendar/core/locales/pt-br'

window.Calendar = Calendar
window.dayGridPlugin = dayGridPlugin
window.interactionPlugin = interactionPlugin
window.ptBrLocale = ptBrLocale

import IMask from 'imask';
window.IMask = IMask;