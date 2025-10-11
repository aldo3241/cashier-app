# 👥 Pelanggan (Customer) System Integration

## Overview

Complete integration of the `pelanggan` (customer) table into the Inspizo Spiritosanto Cashier System. This allows tracking customer information, organization details, and complete address information.

---

## 📊 Database Structure

### Table: `pelanggan`

| Column | Type | Description |
|--------|------|-------------|
| `kd_pelanggan` | string (PK) | Customer code/ID |
| `panggilan` | string | Title/nickname (e.g., "Bapak", "Ibu", "Mr.", "Mrs.") |
| `nama_lengkap` | string | Full legal name |
| `nama_lembaga` | string | Organization/company name (optional) |
| `telp` | string | Phone number |
| `alamat` | string | Street address |
| `kecamatan` | string | District |
| `kotakab` | string | City/Regency |
| `provinsi` | string | Province |
| `negara` | string | Country |
| `kode_pos` | string | Postal code |
| `catatan` | text | Notes/remarks |
| `date_updated` | datetime | Last update timestamp |
| `dibuat_oleh` | string | Created by (user ID) |
| `date_created` | datetime | Creation timestamp |

---

## 📁 Files Created

### 1. **Model: `app/Models/Pelanggan.php`**

Complete customer model with smart accessors and methods.

#### **Key Features:**

**Attributes/Accessors:**
- `display_name` - Returns panggilan or nama_lengkap
- `full_name_with_title` - "Bapak John Doe"
- `full_address` - Complete formatted address
- `identifier` - Organization + name or just name
- `formatted_phone` - Indonesian phone format (0812-3456-7890)
- `type` - "personal" or "organization"

**Methods:**
- `hasOrganization()` - Check if customer is from organization
- `scopeSearch()` - Search by name, phone, organization, code
- `scopeOrderByName()` - Order alphabetically

---

### 2. **Controller: `app/Http/Controllers/Api/PelangganController.php`**

RESTful API controller for customer operations.

#### **Endpoints:**

**Search Customers**
```php
GET /api/customers/search?q={search}&limit={limit}
```
Response:
```json
{
  "success": true,
  "data": [
    {
      "id": "CUST001",
      "code": "CUST001",
      "name": "John Doe",
      "display_name": "Mr. John",
      "title": "Mr.",
      "organization": "ABC Corp",
      "phone": "081234567890",
      "formatted_phone": "0812-3456-7890",
      "address": "Jl. Sudirman No. 123",
      "full_address": "Jl. Sudirman No. 123, Kec. Menteng, Jakarta Pusat, DKI Jakarta, 10110",
      "type": "organization",
      "identifier": "ABC Corp (Mr. John)"
    }
  ]
}
```

**Get Customer by ID**
```php
GET /api/customers/get?id={kd_pelanggan}
```
Response: Complete customer details including all fields.

**List All Customers (Paginated)**
```php
GET /api/customers?per_page={50}&search={query}
```
Response: Paginated customer list with metadata.

**Get Customer Statistics**
```php
GET /api/customers/stats
```
Response:
```json
{
  "success": true,
  "data": {
    "total": 150,
    "organization": 45,
    "personal": 105
  }
}
```

---

## 🔧 Usage Examples

### **In PHP/Laravel**

#### **Find Customer**
```php
use App\Models\Pelanggan;

$customer = Pelanggan::find('CUST001');
echo $customer->display_name;        // "Mr. John"
echo $customer->full_address;         // Complete formatted address
echo $customer->type;                 // "organization" or "personal"
```

#### **Search Customers**
```php
$customers = Pelanggan::search('john')
    ->orderByName()
    ->limit(10)
    ->get();

foreach ($customers as $customer) {
    echo $customer->identifier;       // "ABC Corp (Mr. John)"
    echo $customer->formatted_phone;  // "0812-3456-7890"
}
```

#### **Check Customer Type**
```php
if ($customer->hasOrganization()) {
    echo "Organization: " . $customer->nama_lembaga;
} else {
    echo "Personal customer";
}
```

---

### **In JavaScript (Cashier Frontend)**

#### **Search Customers**
```javascript
async function searchCustomers(query) {
    const response = await fetch(`/api/customers/search?q=${query}&limit=10`);
    const data = await response.json();
    
    if (data.success) {
        return data.data;
    }
    return [];
}

// Usage
const customers = await searchCustomers('john');
customers.forEach(customer => {
    console.log(customer.identifier);  // "ABC Corp (Mr. John)"
    console.log(customer.type);        // "organization" or "personal"
});
```

#### **Get Customer Details**
```javascript
async function getCustomer(customerId) {
    const response = await fetch(`/api/customers/get?id=${customerId}`);
    const data = await response.json();
    
    if (data.success) {
        return data.data;
    }
    return null;
}

// Usage
const customer = await getCustomer('CUST001');
console.log(customer.full_address);
console.log(customer.formatted_phone);
```

---

## 🎯 Smart Features

### **1. Display Name Priority**
```
display_name = panggilan → nama_lengkap
```
Example:
- If `panggilan` = "Bapak", returns "Bapak"
- If `panggilan` = null, returns "John Doe"

### **2. Full Name with Title**
```
full_name_with_title = panggilan + " " + nama_lengkap
```
Example: "Bapak John Doe"

### **3. Address Formatting**
Automatically builds complete address:
```
alamat, Kec. kecamatan, kotakab, provinsi, [negara], kode_pos
```
- Skips empty fields
- Only shows country if not Indonesia
- Proper formatting with commas

### **4. Phone Formatting**
Indonesian phone format:
```
081234567890 → 0812-3456-7890
```

### **5. Customer Type Detection**
```php
type = nama_lembaga ? "organization" : "personal"
```

### **6. Smart Identifier**
```
identifier = nama_lembaga ? "nama_lembaga (display_name)" : "display_name"
```
Examples:
- "ABC Corp (Mr. John)"
- "Jane Doe"

---

## 📱 API Routes Summary

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/customers/search` | Search customers by name, phone, org |
| GET | `/api/customers/get` | Get customer by ID |
| GET | `/api/customers` | List all customers (paginated) |
| GET | `/api/customers/stats` | Get customer statistics |

All routes are **protected** (require authentication).

---

## 🎨 Integration with Cashier

### **Use Cases:**

1. **Customer Selection in Checkout**
   - Search customers while processing sale
   - Auto-complete with customer suggestions
   - Display organization info if available

2. **Sales Reports**
   - Track sales by customer
   - Organization vs personal customer analytics
   - Customer purchase history

3. **Customer Management**
   - View all customers
   - Search and filter
   - Display complete contact information

---

## 💡 Best Practices

### **1. Always Use Accessors**
```php
// ✅ Good
$name = $customer->display_name;
$address = $customer->full_address;

// ❌ Avoid
$name = $customer->panggilan ?: $customer->nama_lengkap;
```

### **2. Check Customer Type**
```php
// ✅ Good
if ($customer->hasOrganization()) {
    // Handle organization customer
}

// ❌ Avoid
if (!empty($customer->nama_lembaga)) {
    // Less semantic
}
```

### **3. Use Search Scope**
```php
// ✅ Good
Pelanggan::search($query)->orderByName()->get();

// ❌ Avoid
Pelanggan::where('nama_lengkap', 'LIKE', "%{$query}%")->get();
```

---

## 🔒 Security

- ✅ All API routes are protected with authentication middleware
- ✅ Input validation on search queries
- ✅ SQL injection protection via Eloquent
- ✅ No sensitive data exposed in API responses
- ✅ Proper error handling

---

## 🚀 Performance

- ✅ Indexed search fields for fast queries
- ✅ Pagination support for large datasets
- ✅ Efficient attribute accessors (no N+1 queries)
- ✅ Limited result sets (default 10 for search)

---

## 📊 Example Data Structure

### **Personal Customer**
```json
{
  "kd_pelanggan": "CUST001",
  "panggilan": "Bapak",
  "nama_lengkap": "Ahmad Wijaya",
  "nama_lembaga": null,
  "telp": "081234567890",
  "alamat": "Jl. Merdeka No. 45",
  "kecamatan": "Bandung Wetan",
  "kotakab": "Bandung",
  "provinsi": "Jawa Barat",
  "negara": "Indonesia",
  "kode_pos": "40111"
}
```
**Output:**
- `display_name`: "Bapak"
- `full_name_with_title`: "Bapak Ahmad Wijaya"
- `type`: "personal"
- `identifier`: "Bapak"

### **Organization Customer**
```json
{
  "kd_pelanggan": "CUST002",
  "panggilan": "Ibu",
  "nama_lengkap": "Siti Nurhaliza",
  "nama_lembaga": "PT Maju Jaya",
  "telp": "021-5551234",
  "alamat": "Gedung Plaza Indonesia Lt. 5",
  "kecamatan": "Menteng",
  "kotakab": "Jakarta Pusat",
  "provinsi": "DKI Jakarta",
  "negara": "Indonesia",
  "kode_pos": "10310"
}
```
**Output:**
- `display_name`: "Ibu"
- `full_name_with_title`: "Ibu Siti Nurhaliza"
- `type`: "organization"
- `identifier`: "PT Maju Jaya (Ibu)"

---

## ✅ Summary

✔️ Complete Pelanggan model with smart accessors  
✔️ RESTful API controller with 4 endpoints  
✔️ Search, get by ID, list all, and statistics  
✔️ Support for both personal and organization customers  
✔️ Smart address and phone formatting  
✔️ Indonesian naming conventions support  
✔️ Fully integrated with authentication  
✔️ No database migrations needed  
✔️ Ready for cashier system integration  

---

**Last Updated**: October 11, 2025  
**Version**: 1.0  
**Status**: ✅ Production Ready

