(function () {
    if (window.SPARK_LANGUAGE !== 'th') return;

    const translations = {
        'HOME':'หน้าแรก','SHOP':'ร้านค้า','MEN':'ผู้ชาย','WOMEN':'ผู้หญิง','KIDS':'เด็ก','SALE':'ลดราคา','BLOG':'บทความ',
        'Login':'เข้าสู่ระบบ','Logout':'ออกจากระบบ','Sign Up':'สมัครสมาชิก','Create an account':'สร้างบัญชี','Shopping cart':'ตะกร้าสินค้า',
        'Search products':'ค้นหาสินค้า','Search for more than 20,000 products':'ค้นหาสินค้ามากกว่า 20,000 รายการ','All Categories':'ทุกหมวดหมู่',
        'NEW ARRIVAL':'สินค้าใหม่','Step Up Your Style':'ยกระดับสไตล์ของคุณ','Premium comfort. Timeless design. Made for every move you make.':'ความสบายระดับพรีเมียม ดีไซน์เหนือกาลเวลา พร้อมสำหรับทุกการเคลื่อนไหว',
        'Shop Now':'ช้อปเลย','Explore Collection':'ดูคอลเลกชัน','Free Shipping':'จัดส่งฟรี','Secure Payment':'ชำระเงินปลอดภัย','Premium Quality':'คุณภาพพรีเมียม','24/7 Support':'ช่วยเหลือตลอด 24 ชั่วโมง',
        'On qualifying orders':'สำหรับคำสั่งซื้อที่ร่วมรายการ','Protected checkout':'ระบบชำระเงินที่ปลอดภัย','Carefully selected':'คัดสรรอย่างพิถีพิถัน','Always here to help':'พร้อมช่วยเหลือเสมอ','Trending Products':'สินค้ายอดนิยม',
        'My Account':'บัญชีของฉัน','My Profile':'โปรไฟล์ของฉัน','My Orders':'คำสั่งซื้อของฉัน','Addresses':'ที่อยู่','Payment Methods':'วิธีการชำระเงิน','Settings':'ตั้งค่า',
        'Shipping Addresses':'ที่อยู่จัดส่ง','Add Address':'เพิ่มที่อยู่','Edit Address':'แก้ไขที่อยู่','Label':'ชื่อที่อยู่','Full name':'ชื่อ-นามสกุล','Full Name':'ชื่อ-นามสกุล','Phone':'โทรศัพท์','Phone number':'หมายเลขโทรศัพท์',
        'Address line':'รายละเอียดที่อยู่','Subdistrict':'ตำบล/แขวง','District':'อำเภอ/เขต','Province':'จังหวัด','Postal code':'รหัสไปรษณีย์','Country':'ประเทศ','Set as default':'ตั้งเป็นค่าเริ่มต้น','Save Address':'บันทึกที่อยู่','Edit':'แก้ไข','Delete':'ลบ','Default':'ค่าเริ่มต้น','Set Default':'ตั้งเป็นค่าเริ่มต้น',
        'Language':'ภาษา','English':'อังกฤษ','Thai':'ไทย','Currency':'สกุลเงิน','Order updates':'อัปเดตคำสั่งซื้อ','Promotions':'โปรโมชั่น','Email notifications':'การแจ้งเตือนทางอีเมล','Save Settings':'บันทึกการตั้งค่า',
        'Change Password':'เปลี่ยนรหัสผ่าน','Current password':'รหัสผ่านปัจจุบัน','New password (8+ characters)':'รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)','Profile updated.':'อัปเดตโปรไฟล์แล้ว','Settings saved.':'บันทึกการตั้งค่าแล้ว','Address saved.':'บันทึกที่อยู่แล้ว','Address removed.':'ลบที่อยู่แล้ว','Default address updated.':'อัปเดตที่อยู่เริ่มต้นแล้ว',
        'Save Profile':'บันทึกโปรไฟล์','Birthday (optional)':'วันเกิด (ไม่บังคับ)','Gender (optional)':'เพศ (ไม่บังคับ)','Prefer not to say':'ไม่ระบุ','Female':'หญิง','Male':'ชาย','Nonbinary':'นอนไบนารี',
        'Shopping Cart':'ตะกร้าสินค้า','Shipping':'การจัดส่ง','Payment':'การชำระเงิน','Select All':'เลือกทั้งหมด','Delete selected':'ลบรายการที่เลือก','Order Summary':'สรุปคำสั่งซื้อ','Subtotal':'ยอดรวมสินค้า','Shipping Fee':'ค่าจัดส่ง','Shipping fee':'ค่าจัดส่ง','Discount':'ส่วนลด','Total':'ยอดรวม','Checkout':'ชำระเงิน','Continue Shopping':'ช้อปต่อ','Your cart is empty':'ตะกร้าของคุณว่างเปล่า','Start shopping':'เริ่มช้อปปิ้ง','Apply':'ใช้','Enter voucher code':'กรอกรหัสส่วนลด',
        'Shipping information':'ข้อมูลการจัดส่ง','Choose a saved address or enter the delivery address.':'เลือกที่อยู่ที่บันทึกไว้หรือกรอกที่อยู่จัดส่ง','Saved addresses':'ที่อยู่ที่บันทึกไว้','Use a new address':'ใช้ที่อยู่ใหม่','Email':'อีเมล','Street address':'ที่อยู่','City / District':'เมือง / เขต','Continue to Payment →':'ดำเนินการชำระเงิน →','← Back to Shopping Cart':'← กลับไปตะกร้าสินค้า',
        'Payment method':'วิธีการชำระเงิน','Credit / Debit Card':'บัตรเครดิต / เดบิต','PromptPay QR':'พร้อมเพย์ QR','Bank Transfer':'โอนเงินผ่านธนาคาร','Cash on Delivery':'เก็บเงินปลายทาง','Place Order':'ยืนยันคำสั่งซื้อ','← Back to Shipping':'← กลับไปข้อมูลจัดส่ง','Shipping to':'จัดส่งไปยัง',
        'Add to Cart':'เพิ่มลงตะกร้า','Buy Now':'ซื้อเลย','Size':'ขนาด','Product Description':'รายละเอียดสินค้า','Reviews':'รีวิว','Shipping & Returns':'การจัดส่งและการคืนสินค้า',
        'Order placed successfully':'สั่งซื้อสำเร็จ','Order ID':'หมายเลขคำสั่งซื้อ','Order date':'วันที่สั่งซื้อ','Payment status':'สถานะการชำระเงิน','Ordered products':'สินค้าที่สั่งซื้อ','Shipping address':'ที่อยู่จัดส่ง',
        'Free delivery':'จัดส่งฟรี','100% secure payment':'ชำระเงินปลอดภัย 100%','Quality guarantee':'รับประกันคุณภาพ','Guaranteed savings':'รับประกันความคุ้มค่า','Daily offers':'ข้อเสนอประจำวัน',
        'About us':'เกี่ยวกับเรา','Conditions':'ข้อกำหนด','Careers':'ร่วมงานกับเรา','Affiliate Programme':'โปรแกรมพันธมิตร','Customer Service':'บริการลูกค้า','Contact':'ติดต่อเรา','Privacy Policy':'นโยบายความเป็นส่วนตัว','Returns':'การคืนสินค้า','Subscribe':'สมัครรับข่าวสาร','Email Address':'ที่อยู่อีเมล','© 2025 Spark. All rights reserved.':'© 2025 Spark สงวนลิขสิทธิ์',
        "MEN'S COLLECTION":'คอลเลกชันผู้ชาย',"WOMEN'S COLLECTION":'คอลเลกชันผู้หญิง',"KIDS' COLLECTION":'คอลเลกชันเด็ก','New Arrivals':'สินค้าใหม่','Sneakers':'รองเท้าผ้าใบ','Running':'รองเท้าวิ่ง','Basketball':'บาสเกตบอล','Lifestyle':'ไลฟ์สไตล์','Best Sellers':'สินค้าขายดี','All':'ทั้งหมด',
        'Please login to continue shopping.':'กรุณาเข้าสู่ระบบเพื่อช้อปปิ้งต่อ','Please select a size':'กรุณาเลือกขนาด','Please select at least one product.':'กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ','Remove this saved address?':'ต้องการลบที่อยู่นี้หรือไม่'
    };
    const nativeAlert = window.alert.bind(window);
    const nativeConfirm = window.confirm.bind(window);
    window.sparkT = value => translations[value] || value;
    window.alert = message => nativeAlert(window.sparkT(String(message)));
    window.confirm = message => nativeConfirm(window.sparkT(String(message)));

    function translateText(node) {
        if (!node.nodeValue || !node.nodeValue.trim()) return;
        const original = node.nodeValue.trim();
        if (translations[original]) node.nodeValue = node.nodeValue.replace(original, translations[original]);
    }

    function translateElement(element) {
        if (!(element instanceof Element)) return;
        if (element.matches('input[placeholder],textarea[placeholder]')) {
            const value = element.getAttribute('placeholder');
            if (translations[value]) element.setAttribute('placeholder', translations[value]);
        }
        element.querySelectorAll('input[placeholder],textarea[placeholder]').forEach(input => {
            const value = input.getAttribute('placeholder');
            if (translations[value]) input.setAttribute('placeholder', translations[value]);
        });
        const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
        let node;
        while ((node = walker.nextNode())) translateText(node);
    }

    function translatePage() {
        document.documentElement.lang = 'th';
        translateElement(document.body);
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', translatePage);
    else translatePage();

    const observer = new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(node => {
        if (node.nodeType === Node.TEXT_NODE) translateText(node);
        else translateElement(node);
    })));
    observer.observe(document.documentElement, {childList:true, subtree:true});
})();
