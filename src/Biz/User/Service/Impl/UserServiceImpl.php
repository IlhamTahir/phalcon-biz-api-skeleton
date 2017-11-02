<?php
namespace Biz\User\Service\Impl;

use Biz\User\Dao\UserDao;
use Biz\User\Service\UserService;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;
use Webpatser\Uuid\Uuid;

class UserServiceImpl extends BaseService implements UserService
{
    public function getUser($id)
    {
        return $this->getUserDao()->get($id);
    }

    public function searchUsers($conditions, $sorts, $start, $limit)
    {
        return $this->getUserDao()->search($conditions, $sorts, $start, $limit);
    }

    public function countUsers($conditions)
    {
        return $this->getUserDao()->count($conditions);
    }

    public function createUser($user)
    {
        $user = $this->biz['validator']->validate($user, [
            'username' => 'required|string|length_between:3,18',
            'password' => 'required|string|length_between:3,32'
        ]);

        $existUser = $this->getUserDao()->getByUsername($user['username']);
        if ($existUser) {
            throw new InvalidArgumentException("用户名已存在，注册失败！");
        }

        $user['salt'] = Uuid::generate(4);
        $user['password'] = $this->encodePassword($user['password'], $user['salt']);
        
        return $this->getUserDao()->create($user);
    }

    public function banUser($id)
    {

    }

    protected function encodePassword($password, $salt)
    {
        $options = [
            'salt' => $salt,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    /**
     * @return \Biz\User\Dao\UserDao
     */
    protected function getUserDao()
    {
        return $this->biz->dao('User:UserDao');
    }
}
