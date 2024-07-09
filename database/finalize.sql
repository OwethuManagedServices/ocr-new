USE omsobse;
ALTER TABLE acl_permissions AUTO_INCREMENT=10001;
ALTER TABLE acl_roles AUTO_INCREMENT=10001;
ALTER TABLE acl_roles_permissions AUTO_INCREMENT=10001;
ALTER TABLE acl_users_permissions AUTO_INCREMENT=10001;
ALTER TABLE acl_users_roles AUTO_INCREMENT=10001;
ALTER TABLE failed_jobs AUTO_INCREMENT=10001;
ALTER TABLE jobs AUTO_INCREMENT=10001;
ALTER TABLE password_reset_tokens AUTO_INCREMENT=10001;
ALTER TABLE personal_access_tokens AUTO_INCREMENT=10001;
ALTER TABLE sessions AUTO_INCREMENT=10001;
ALTER TABLE team_invitations AUTO_INCREMENT=10001;
ALTER TABLE team_user AUTO_INCREMENT=10001;
ALTER TABLE teams AUTO_INCREMENT=10001;
ALTER TABLE users AUTO_INCREMENT=10001;
ALTER TABLE website AUTO_INCREMENT=10001;


INSERT INTO `acl_permissions` VALUES ('10001', 'Admin - Permissions Grid', 'admin-permission.index', '2024-05-08 12:00:00', '2024-03-08 19:24:07');
INSERT INTO `acl_permissions` VALUES ('10002', 'Admin - Permission Add Save', 'admin-permission.store', '2024-05-08 12:00:00', '2024-03-08 19:37:23');
INSERT INTO `acl_permissions` VALUES ('10003', 'Admin - Permission Add Screen', 'admin-permission.create', '2024-05-08 12:00:00', '2024-03-08 19:35:23');
INSERT INTO `acl_permissions` VALUES ('10004', 'Admin - Permission Route Update', 'admin-permission.routes.update', '2024-05-08 12:00:00', '2024-03-08 19:36:00');
INSERT INTO `acl_permissions` VALUES ('10005', 'Admin - Permission Update', 'admin-permission.update', '2024-05-08 12:00:00', '2024-03-08 19:51:35');
INSERT INTO `acl_permissions` VALUES ('10006', 'Admin - Permission Delete', 'admin-permission.destroy', '2024-05-08 12:00:00', '2024-03-08 20:06:12');
INSERT INTO `acl_permissions` VALUES ('10007', 'Admin - Permission Edit Screen', 'admin-permission.edit', '2024-05-08 12:00:00', '2024-03-08 20:13:21');
INSERT INTO `acl_permissions` VALUES ('10008', 'Admin - Permission Roles', 'admin-permission.roles', '2024-05-08 12:00:00', '2024-03-08 20:14:32');
INSERT INTO `acl_permissions` VALUES ('10009', 'Admin - Permission Roles Update', 'admin-permission.rolesupdate', '2024-05-08 12:00:00', '2024-03-08 20:15:03');
INSERT INTO `acl_permissions` VALUES ('10010', 'Admin - Permission Users', 'admin-permission.users', '2024-05-08 12:00:00', '2024-03-08 20:16:53');
INSERT INTO `acl_permissions` VALUES ('10011', 'Admin- Roles Grid', 'admin-role.index', '2024-05-08 12:00:00', '2024-03-08 20:18:08');
INSERT INTO `acl_permissions` VALUES ('10012', 'Admin - Role Add Save', 'admin-role.store', '2024-05-08 12:00:00', '2024-03-08 20:18:33');
INSERT INTO `acl_permissions` VALUES ('10013', 'Admin - Role Add Screen', 'admin-role.create', '2024-05-08 12:00:00', '2024-03-08 20:18:55');
INSERT INTO `acl_permissions` VALUES ('10014', 'Admin - Role Update', 'admin-role.update', '2024-05-08 12:00:00', '2024-03-08 20:19:31');
INSERT INTO `acl_permissions` VALUES ('10015', 'Admin - Role Destroy', 'admin-role.destroy', '2024-05-08 12:00:00', '2024-03-08 20:19:49');
INSERT INTO `acl_permissions` VALUES ('10016', 'Admin - Role Edit Screen', 'admin-role.edit', '2024-05-08 12:00:00', '2024-03-08 20:20:17');
INSERT INTO `acl_permissions` VALUES ('10017', 'Admin - Users Grid', 'admin-users.index', '2024-05-08 12:00:00', '2024-03-08 20:20:40');
INSERT INTO `acl_permissions` VALUES ('10018', 'Admin - Users Add Save', 'admin-users.store', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10019', 'Admin - Users Add Screen', 'admin-users.create', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10020', 'Admin - Users Update', 'admin-users.update', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10021', 'Admin - Users Delete', 'admin-users.destroy', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10022', 'Admin - Users Edit Screen', 'admin-users.edit', '2024-05-08 12:00:00', '2024-05-08 12:00:00');
INSERT INTO `acl_permissions` VALUES ('10023', 'User - Logout', 'user-logout', '2024-03-12 13:24:39', '2024-03-12 13:24:39');
INSERT INTO `acl_permissions` VALUES ('10024', 'User - Livewire Preview File', 'user-livewire-preview-file', '2024-03-12 13:26:22', '2024-03-12 13:26:22');
INSERT INTO `acl_permissions` VALUES ('10025', 'User - Livewire Update', 'user-livewire-update', '2024-03-12 13:26:55', '2024-03-12 13:26:55');
INSERT INTO `acl_permissions` VALUES ('10026', 'User - Livewire Upload File', 'user-livewire-upload-file', '2024-03-12 13:27:58', '2024-03-12 13:27:58');
INSERT INTO `acl_permissions` VALUES ('10027', 'Admin Dashboard', 'dashboard', '2024-03-12 13:28:57', '2024-03-12 13:28:57');
INSERT INTO `acl_permissions` VALUES ('10028', 'User - Two Factor Secret Key', 'user-two-factor.secret-key', '2024-03-12 13:59:06', '2024-03-12 13:59:06');
INSERT INTO `acl_permissions` VALUES ('10029', 'User Two Factor Recovery Codes', 'user-two-factor.recovery-codes', '2024-03-12 13:59:55', '2024-03-12 13:59:55');
INSERT INTO `acl_permissions` VALUES ('10030', 'User - Two Factor QR Code', 'user-two-factor.qr-code', '2024-03-12 14:00:49', '2024-03-12 14:00:49');
INSERT INTO `acl_permissions` VALUES ('10031', 'User Two Factor Login', 'user-two-factor.login', '2024-03-12 14:01:27', '2024-03-12 14:01:27');
INSERT INTO `acl_permissions` VALUES ('10032', 'User - Two Factor Enable', 'user-two-factor.enable', '2024-03-12 14:02:00', '2024-03-12 14:02:00');
INSERT INTO `acl_permissions` VALUES ('10033', 'User - Two Factor Disable', 'user-two-factor.disable', '2024-03-12 14:02:31', '2024-03-12 14:02:31');
INSERT INTO `acl_permissions` VALUES ('10034', 'User Two Factor Confirm', 'user-two-factor.confirm', '2024-03-12 14:03:01', '2024-03-12 14:03:01');
INSERT INTO `acl_permissions` VALUES ('10035', 'User - Profile Show', 'user-profile-show', '2024-03-12 14:05:37', '2024-03-12 14:05:37');
INSERT INTO `acl_permissions` VALUES ('10036', 'User - Teams Create', 'user-teams.create', '2024-03-12 14:10:29', '2024-03-12 14:10:29');
INSERT INTO `acl_permissions` VALUES ('10037', 'User - Teams Show', 'user-teams.show', '2024-03-12 14:11:02', '2024-03-12 14:11:02');
INSERT INTO `acl_permissions` VALUES ('10038', 'User - Current Team Update', 'user-current-team.update', '2024-03-12 14:11:33', '2024-03-12 14:11:33');
INSERT INTO `acl_permissions` VALUES ('10039', 'User Team Invitations Accept', 'user-team-invitations.accept', '2024-03-12 14:12:04', '2024-03-12 14:12:04');
INSERT INTO `acl_permissions` VALUES ('10040', 'User - API Tokens', 'user-api-tokens.index', '2024-03-12 14:15:00', '2024-03-12 14:15:00');
INSERT INTO `acl_permissions` VALUES ('10041', 'User - Password Update', 'user-password.update', null, null);
INSERT INTO `acl_permissions` VALUES ('10042', 'User - Password Confirmation', 'password.confirmation', null, null);
INSERT INTO `acl_permissions` VALUES ('10043', 'User -Password Confirm', 'password.confirm', null, null);
INSERT INTO `acl_permissions` VALUES ('10044', 'User - Sanctum CSRF Cookie', 'user-sanctum.csrf-cookie', null, null);




INSERT INTO `acl_roles` VALUES ('10001', 'Admin - Access Control Menu', 'admin-access-control', '2024-05-08 12:00:00', '2024-03-08 22:05:52');
INSERT INTO `acl_roles` VALUES ('10002', 'Admin - Users Menu', 'admin-users', '2024-05-08 12:00:00', '2024-03-08 22:06:01');
INSERT INTO `acl_roles` VALUES ('10003', 'Frontend - Website Menu', 'frontend-website', '2024-05-08 12:00:00', '2024-03-08 22:07:29');
INSERT INTO `acl_roles` VALUES ('10004', 'User - System Essentials', 'user-system-essentials', '2024-03-12 13:29:58', '2024-03-12 13:29:58');
INSERT INTO `acl_roles` VALUES ('10005', 'User - Manage', 'user-manage', '2024-03-12 14:06:24', '2024-03-12 14:13:46');
INSERT INTO `acl_roles` VALUES ('10006', 'User - Teams', 'user-teams', '2024-03-12 14:12:31', '2024-03-12 14:13:57');
INSERT INTO `acl_roles` VALUES ('10007', 'User - API Tokens', 'user-api-tokens', '2024-03-12 14:15:26', '2024-03-12 14:15:26');
INSERT INTO `acl_roles` VALUES ('10008', 'Admin - Admin Dashboard', 'admin-dashboard', '2024-05-08 12:00:00', '2024-05-08 12:00:00');






