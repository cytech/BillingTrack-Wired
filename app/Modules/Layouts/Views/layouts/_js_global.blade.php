<script type="text/javascript">
    // replace jquery $(function () {})
    function ready(fn) {
        if (document.readyState !== 'loading'){
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }
    // function to parse innerhtml and allow script execution (converting from jquery .load())
    // while loading html in #modal-placeholder
    var setInnerHTML = function (elm, html) {
        elm.innerHTML = html;
        Array.from(elm.querySelectorAll("script")).forEach(oldScript => {
            const newScript = document.createElement("script");
            Array.from(oldScript.attributes)
                .forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }
    // jquery .load replacement
    var loadModal = function (route, params = null, placeholder = 'modal-placeholder') {
        if (params) {
            axios.post(route, params)
                .then(response => {
                    setInnerHTML(document.getElementById(placeholder), response.data)
                })
        }else{
            axios.get(route)
                .then(response => {
                    setInnerHTML(document.getElementById(placeholder), response.data)
                })
        }
    }
    // create boostrap modal
    var bsModal = function(selector){
        return new bootstrap.Modal(document.getElementById(selector), {backdrop: 'static'});
        }
    // toast for saving event
    window.addEventListener('swal:saving', event => {
        Swal.fire({
            toast: true,
            title: event.detail.message,
            text: event.detail.text,
            icon: 'info',
            showConfirmButton: false,
        });
    });
    // jquery .toggle replacement
    Element.prototype.toggleid = function() {
        if ( this.style.display === '' || this.style.display === 'block' ) {
            this.style.display = 'none';
        }else{
            this.style.display = 'block';
        }
    }
    // sweetalert dialog for saved event
    window.addEventListener('swal:saved', event => {
        Swal.fire({
            title: event.detail.message,
            text: event.detail.text,
            icon: 'success',
            showConfirmButton: false,
            timer: 2000
        });
    });
    // sweetalert confirmation dialog for saved event
    // emits removeModuleItem for livewire item-table
    window.addEventListener('swal:deleteConfirm', event => {
        Swal.fire({
            title: event.detail.message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d68500',
            confirmButtonText: '@lang('bt.yes_sure')'
        }).then((result) => {
            if (result.isConfirmed) { //not cancelled
                if (event.detail.id) { // has item id
                    axios.post(event.detail.route, { //post to item delete()
                        id: event.detail.id
                    }).then(function (response) {
                        window.livewire.emit('removeModuleItem', event.detail.index)
                        axios.post(event.detail.totalsRoute, {id: event.detail.entityID})
                            .then(response => {
                                setInnerHTML(document.getElementById('div-totals'), response.data)
                            })
                        notify(response.data.message, 'success')
                    }).catch(function (error) {
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            //console.log(error.response.data);
                            notify(error.response.data.message, 'error')
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            console.log(error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            console.log('Error', error.message);
                        }
                        //console.log(error.config);
                    });
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                //notify('Operation cancelled', 'success')
            }
        });
    });
    // sweetalert confirmation dialog for bulk event
    window.addEventListener('swal:bulkConfirm', event => {
    // function bulkConfirm(title, message, route, ids, status) {
        Swal.fire({
            title: event.detail.title,
            html: event.detail.message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d68500',
            confirmButtonText: '@lang('bt.yes_sure')'
        }).then((result) => {
            if (result.value) {
                axios.post(event.detail.route, {
                    ids: event.detail.ids, module_type: event.detail.module_type, status: event.detail.status
                }).then((response) => {
                    window.livewire.emit('reset_bulk_select')
                    notify(response.data.success, 'success');
                }).catch((response) => {
                    notify(response, 'error')
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
    })

    // generic sweetalert notification dialog
    // requires confirm on error, timesout on success
    function notify(message, type) {
        if (type === 'error') {
            sbutton = true;
            stimer = 0;
        } else {
            sbutton = false;
            stimer = 2000;
        }
        Swal.fire({
            title: message,
            icon: type,
            showConfirmButton: sbutton,
            timer: stimer
        });
    }

    function swalSaving(title = '@lang('bt.saving')') {
        Swal.fire({
            toast: true,
            title: title,
            icon: 'info',
            showConfirmButton: false,
        });
    }

    function swalConfirm(title, message, link, target = "body") {
        Swal.fire({
            target: document.getElementById(target),
            title: title,
            html: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d68500',
            confirmButtonText: '@lang('bt.yes_sure')'
        }).then((result) => {
            if (result.value) {
                window.location.href = link;
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        });
    }

    function bulkConfirm(title, message, route, ids, status) {
        Swal.fire({
            title: title,
            html: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d68500',
            confirmButtonText: '@lang('bt.yes_sure')'
        }).then((result) => {
            if (result.value) {
                swalSaving('@lang('bt.working')')
                axios.post(route, {
                    ids: ids,
                    status: status
                }).then((response) => {
                    setTimeout(function () { //give notify a chance to display before redirect
                        window.location.href = "{!! URL::current() !!}";
                    }, 2000);
                    notify(response.data.success, 'success');
                }).catch((response) => {
                    notify(response, 'error')
                })
            } else if (result.dismiss === Swal.DismissReason.cancel) {
            }
        });
    }

    function showErrors(errors, placeholder = '#modal-status-placeholder') {
        document.querySelector(placeholder).innerHTML = ''
        if (errors == null && placeholder) {
            return;
        }
        let msg = ''
        for (let [id, message] of Object.entries(errors)) {
            msg += message + '<br>';
        }
        if (placeholder) document.querySelector(placeholder).innerHTML = '<div class="alert alert-danger">' + msg + '</div>'
    }

    document.addEventListener('DOMContentLoaded', function () {
        let cb_bsa = document.getElementById("bulk-select-all-cb")
        let div_bulk = document.getElementById('bulk-actions')
        //bulk-select-all checkbox
        addEvent(document, 'click', "#bulk-select-all-cb", (e) => {
            if (cb_bsa.checked) {
                document.querySelectorAll('.bulk-record-cb').forEach((cb) => {
                    cb.checked = true
                })
                div_bulk.style.display = "block"
            } else {
                document.querySelectorAll('.bulk-record-cb').forEach((cb) => {
                    cb.checked = false
                })
                div_bulk.style.display = "none"
            }
        })
        //bulk-record checkbox
        addEvent(document, 'click', '.bulk-record-cb', (e) => {
            if (document.querySelectorAll('.bulk-record-cb:checked').length > 0) {
                div_bulk.style.display = "block"
            } else {
                div_bulk.style.display = "none"
            }
        })

        //bulk trash button
        addEvent(document, 'click', '#btn-bulk-trash', (e) => {
            const ids = [];
            document.querySelectorAll('.bulk-record-cb:checked').forEach((cb) => {
                ids.push(cb.dataset.id)
            })
            if (ids.length > 0) {
                bulkConfirm('@lang('bt.bulk_trash_record_warning')', '@lang('bt.bulk_trash_record_warning_msg')', e.target.dataset.route, ids);
            }
        })

        // bulk change status
        addEvent(document, 'click', '.bulk-change-status', (e) => {
            const ids = [];
            document.querySelectorAll('.bulk-record-cb:checked').forEach((cb) => {
                ids.push(cb.dataset.id)
            })
            if (ids.length > 0) {
                bulkConfirm('@lang('bt.bulk_change_status_record_warning')', '', e.target.dataset.route, ids, e.target.dataset.status);
            }
        })

        // common filter options
        addEvent(document, 'change', '.filter_options', (e) => {
            document.getElementById('filter').submit()
        })

        // purchase order receive items
        addEvent(document, 'click', '.receive-purchaseorder', (e) => {
            axios.post('{{ route('purchaseorders.receive') }} ', {purchaseorder_id: e.target.dataset.purchaseorderId})
                .then(response => {
                    setInnerHTML(document.getElementById('modal-placeholder'), response.data)
                })
        })
        // email quote
        addEvent(document, 'click', '.email-quote', (e) => {
            axios.post('{{ route('quoteMail.create') }} ', {
                quote_id: e.target.dataset.quoteId,
                redirectTo: e.target.dataset.redirectTo
            }).then(response => {
                setInnerHTML(document.getElementById('modal-placeholder'), response.data)
            }).catch((response) => {
                notify('@lang('bt.problem_with_email_template')', 'error')
            })
        })

        //email invoice
        addEvent(document, 'click', '.email-invoice', (e) => {
            axios.post('{{ route('invoiceMail.create') }} ', {
                invoice_id: e.target.dataset.invoiceId,
                redirectTo: e.target.dataset.redirectTo
            }).then(response => {
                setInnerHTML(document.getElementById('modal-placeholder'), response.data)
            }).catch((response) => {
                notify('@lang('bt.problem_with_email_template')', 'error')
            })
        })
        //email purchaseorder
        addEvent(document, 'click', '.email-purchaseorder', (e) => {
            axios.post('{{ route('purchaseorderMail.create') }} ', {
                purchaseorder_id: e.target.dataset.purchaseorderId,
                redirectTo: e.target.dataset.redirectTo
            }).then(response => {
                setInnerHTML(document.getElementById('modal-placeholder'), response.data)
            }).catch((response) => {
                notify('@lang('bt.problem_with_email_template')', 'error')
            })
        })
        //scroll to top - START
        document.addEventListener("scroll", handleScroll);
        var scrollToTopBtn = document.querySelector(".back-to-top");

        function handleScroll() {
            var scrollableHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var GOLDEN_RATIO = 0.25;
            if ((document.documentElement.scrollTop / scrollableHeight ) > GOLDEN_RATIO) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        }

        scrollToTopBtn.addEventListener("click", scrollToTop);

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
        //scroll to top - END
    })

    // function to add event listener with callback
    function addEvent(parent, evt, selector, handler) {
        parent.addEventListener(evt, function (event) {
            if (event.target.matches(selector + ', ' + selector + ' *')) {
                handler.apply(event.target.closest(selector), arguments);
            }
        }, false);
    }

    function resizeIframe(obj, minHeight) {
        obj.style.height = '';
        const height = obj.contentWindow.document.body.scrollHeight;

        if (height < minHeight) {
            obj.style.height = minHeight + 'px';
        } else {
            obj.style.height = (height + 50) + 'px';
        }
    }

</script>
