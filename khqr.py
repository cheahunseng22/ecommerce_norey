from bakong_khqr import KHQR

# Create an instance of KHQR with Bakong Developer Token:
khqr = KHQR("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiMjI1ZjUwOGZlM2Y1NDg1YiJ9LCJpYXQiOjE3NjA0NzIxNDYsImV4cCI6MTc2ODI0ODE0Nn0.fqH-qk5XYKi1o-TKxg0vxlzzLXu3-pIA4p8WEe9Gk60")

#Generate QR code data for a transaction:
qr = khqr.create_qr(
    bank_account='cheahun_seng@wing', # Check your user_name@baeyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiMjI1ZjUwOGZlM2Y1NDg1YiJ9LCJpYXQiOjE3NjA0NzIxNDYsImV4cCI6MTc2ODI0ODE0Nn0.fqH-qk5XYKi1o-TKxg0vxlzzLXu3-pIA4p8WEe9Gk60nk under Bakong profile (Mobile App)
    merchant_name='cheahunshop',
    merchant_city='Phnom Penh',
    amount=100, #9800 Riel
    currency='KHR', # USD or KHR
    store_label='MShop',
    phone_number='8550967551164',
    bill_number='TRX01234567',
    terminal_label='Cashier-01',
    static=False # Static or Dynamic QR code (default: False)
)
print("qr:",qr)

# Get Hash MD5
md5 = khqr.generate_md5(qr)
print("md5:",md5)

# Check Transaction paid or unpaid:
payment_status = khqr.check_payment(md5)
print("status:",payment_status)


md5_list = (md5)

qrs = khqr.check_payment(md5_list)
print(qrs)