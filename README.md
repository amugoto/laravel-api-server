### 작업 환경

- PHP 7.4.6
- MariaDB 10.4.13
- nginx 1.19.0
- Laravel 7.0

### 작업내용

- 클라이언트에서 code 값을 서버로 넘겨주면 해당 code 값을 가지고, 카카오측 REST API 에 토큰을 요청함.
  - 데이터 요청시 access token 이 만료가 되었으면 DB에 저장해놓은 refresh token을 가져와서 갱신해주고 header authorization에 새로운 토큰을 넣어줌
