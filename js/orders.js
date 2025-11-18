$(document).ready(function() {
    loadOrders();
    
    function loadOrders() {
        $.ajax({
            url: '../actions/get_orders_action.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                renderOrders(response);
            },
            error: function(xhr) {
                let errorMsg = 'Failed to load orders';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                $('#orders-list').html(`<div class="no-orders"><i class="fas fa-exclamation-circle"></i> ${errorMsg}</div>`);
            }
        });
    }
    
    function renderOrders(orders) {
        if (!orders || orders.length === 0) {
            $('#orders-list').html(`
                <div class="no-orders">
                    <i class="fas fa-box" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p style="margin-top: 15px;">You haven't placed any orders yet</p>
                    <a href="shop.php" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; margin-top: 15px;">
                        <i class="fas fa-store"></i> Start Shopping
                    </a>
                </div>
            `);
            return;
        }
        
        let html = '';
        
        orders.forEach(function(order) {
            let statusClass = 'status-' + order.status.toLowerCase();
            let formattedDate = new Date(order.order_date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            html += `
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-reference">${escapeHtml(order.order_reference)}</div>
                            <div class="order-date">${formattedDate}</div>
                        </div>
                        <span class="status-badge ${statusClass}">${capitalizeFirst(order.status)}</span>
                    </div>
                    
                    <div class="order-items">
            `;
            
            if (order.items && order.items.length > 0) {
                order.items.forEach(function(item) {
                    let imageUrl = item.product_image && item.product_image.startsWith('http') ? 
                        item.product_image : 
                        (item.product_image ? 'http://' + window.location.host + '/' + item.product_image : '');
                    
                    html += `
                        <div class="order-item">
                            <div class="order-item-image">
                                ${imageUrl ? `<img src="${imageUrl}" alt="${escapeHtml(item.product_name)}">` : '<i class="fas fa-image"></i>'}
                            </div>
                            <div class="order-item-details">
                                <div class="order-item-name">${escapeHtml(item.product_name)}</div>
                                <div class="order-item-meta">Qty: ${item.quantity} × ₦${parseFloat(item.unit_price).toFixed(2)}</div>
                            </div>
                            <div class="order-item-price">₦${parseFloat(item.subtotal).toFixed(2)}</div>
                        </div>
                    `;
                });
            }
            
            html += `
                    </div>
                    
                    <div class="order-footer">
                        <div>Order Total</div>
                        <div class="order-total">₦${parseFloat(order.total_amount).toFixed(2)}</div>
                    </div>
                </div>
            `;
        });
        
        $('#orders-list').html(html);
    }
    
    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    function escapeHtml(text) {
        let map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});