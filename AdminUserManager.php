

namespace Admin\Service;

use Admin\Entity\AdminUser;
use Admin\Entity\AdminUserGroup;
use Doctrine\ORM\EntityManager;

class AdminUserGroupManager
{
    private $entityManager;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 添加管理员组
     * @param array $data
     * @return AdminUserGroup
     */
    public function addAdminGroup(array $data)
    {
        $adminGroup = new AdminUserGroup();
        $adminGroup->valuesSet($data);
        if(!empty($data['adminGroupPermission'])) {
            $adminGroup->setAdminGroupPurview(implode(',', $data['adminGroupPermission']));
        }

        $this->entityManager->persist($adminGroup);
        $this->entityManager->flush();

        return $adminGroup;
    }

    /**
     * 更新管理员组
     * @param $adminGroup
     * @param $data
     * @return bool
     */
    public function updateAdminGroup(AdminUserGroup $adminGroup, $data)
    {
        $adminGroup->valuesSet($data);
        if(!empty($data['adminGroupPermission'])) {
            $adminGroup->setAdminGroupPurview(implode(',', $data['adminGroupPermission']));
        } else $adminGroup->setAdminGroupPurview('');

        $this->entityManager->flush();

        return true;
    }
    /**
     * 删除管理员组
     * @param $adminGroup
     * @return bool
     */
    public function deleteAdminGroup($adminGroup)
    {
        $adminUser = $this->entityManager->getRepository(AdminUser::class)->findOneBy(['adminGroupId'=>$adminGroup->getAdminGroupId()]);
        if($adminUser == null) {
            $this->entityManager->remove($adminGroup);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }
}
 

namespace Admin\Service;

use Admin\Entity\AdminUser;
use Admin\Entity\AdminUserGroup;
use Doctrine\ORM\EntityManager;

class AdminUserGroupManager
{
    private $entityManager;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 添加管理员组
     * @param array $data
     * @return AdminUserGroup
     */
    public function addAdminGroup(array $data)
    {
        $adminGroup = new AdminUserGroup();
        $adminGroup->valuesSet($data);
        if(!empty($data['adminGroupPermission'])) {
            $adminGroup->setAdminGroupPurview(implode(',', $data['adminGroupPermission']));
        }

        $this->entityManager->persist($adminGroup);
        $this->entityManager->flush();

        return $adminGroup;
    }

    /**
     * 更新管理员组
     * @param $adminGroup
     * @param $data
     * @return bool
     */
    public function updateAdminGroup(AdminUserGroup $adminGroup, $data)
    {
        $adminGroup->valuesSet($data);
        if(!empty($data['adminGroupPermission'])) {
            $adminGroup->setAdminGroupPurview(implode(',', $data['adminGroupPermission']));
        } else $adminGroup->setAdminGroupPurview('');

        $this->entityManager->flush();

        return true;
    }
    /**
     * 删除管理员组
     * @param $adminGroup
     * @return bool
     */
    public function deleteAdminGroup($adminGroup)
    {
        $adminUser = $this->entityManager->getRepository(AdminUser::class)->findOneBy(['adminGroupId'=>$adminGroup->getAdminGroupId()]);
        if($adminUser == null) {
            $this->entityManager->remove($adminGroup);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }
}


namespace Admin\Service;

use Admin\Entity\AdminUser;
use Admin\Entity\AdminUserGroup;
use Doctrine\ORM\EntityManager;

class AdminUserManager
{
    private $entityManager;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }
    /**
     * 添加管理员
     * @param $data
     * @return AdminUser
     */
    public function addAdminUser($data)
    {
        $group = $this->entityManager->getRepository(AdminUserGroup::class)->findOneByAdminGroupId($data['adminGroupId']);

        $adminUser = new AdminUser();
        $data['adminAddTime']   = time();
        $adminUser->valuesSet($data);
        $adminUser->setGroup($group);

        $this->entityManager->persist($adminUser);

        $this->entityManager->flush();
        return $adminUser;
    }

    /**
     * 更新管理员信息
     * @param $user
     * @param $data
     * @return bool
     */
    public function updateAdminUser(AdminUser $user, $data)
    {
        $group = $this->entityManager->getRepository(AdminUserGroup::class)->findOneByAdminGroupId($data['adminGroupId']);

        if($user->getAdminEmail() != $data['adminEmail']) $user->setAdminEmail($data['adminEmail']);
        if($user->getAdminGroupId() != $data['adminGroupId']) {
            $user->setAdminGroupId($data['adminGroupId']);
            $user->setGroup($group);
        }

        $user->setAdminState($data['adminState']);

        $this->entityManager->flush();
        return true;
    }

    /**
     * 重置密码
     * @param AdminUser $user
     * @param array $data
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeAdminPassword(AdminUser $user, array $data)
    {
        $adminUser = new AdminUser();
        $adminUser->setAdminPassword($data['adminPassword']);

        if($user->getAdminPassword() != $adminUser->getAdminPassword()) {
            $user->setAdminPassword($data['adminPassword']);

            $this->entityManager->flush();
        }

        return true;
    }

    /**
     * 删除账户
     * @param $user
     */
    public function deleteUser($user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
