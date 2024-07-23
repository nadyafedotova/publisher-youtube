# Recommendation API

GET /api/v1/book/{book_id}/recommendations
Authorization: Server {token}

## 200

```json
{
    "id": 1,
    "ts": 123123123,
    "items": [
        {"id": 1}
    ]
}
```

## 400

```json
{
    "error": "access denied"
}
```
